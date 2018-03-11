<?php

namespace LogisticsBundle\Controller;

use Doctrine\ORM\QueryBuilder;
use LogisticsBundle\Entity\OrderDetail;
use LogisticsBundle\Entity\PurchaseOrder;
use LogisticsBundle\Model\OrderManager;
use LogisticsBundle\Repository\PurchaseOrderRepository;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class OrdersController
 * @package LogisticsBundle\Controller
 */
class OrdersController extends Controller
{
    /**
     * POST a list of purchase orders
     *
     * @param Request $request
     * @return Response
     *
     * @ApiDoc(
     *     resource="Operations on purchase orders.",
     *     resourceDescription="Operations on purchase orders.",
     *     section="Orders",
     *     description="Create purchase orders.",
     *     statusCodes={
     *          201="Returned when successful"
     *     },
     *     headers={{"name"="Authorization", "required"=true,"description"="Bearer token"}}
     * )
     *
     *
     */
    public function postOrdersAction(Request $request) {
        $content = $request->getContent();

        $data = json_decode($content, true);

        $orderManager = $this->get('logistics.order_manager');
        $orders = $orderManager->decodeFromJson($data);

        $em = $this->getDoctrine()->getManager();

        $batchSize = 25;
        $count = 0;

        foreach ($orders as $order)
        {
            $em->persist($order);
            $count++;
            if (($count % $batchSize) == 0 or count($orders) == $count)
            {
                $em->flush();
                $em->clear();
            }
        }

        $response = new Response();
        $response->setStatusCode(Response::HTTP_CREATED);
        return $response;

    }

    /**
     * GET the list of open purchase orders
     *
     * @return JsonResponse containing the list of purchase orders where the processedDate is null
     *
     * @ApiDoc(
     *     resource="Operations on purchase orders.",
     *     resourceDescription="Operations on purchase orders.",
     *     section="Orders",
     *     description="List all open purchase orders.",
     *     statusCodes={
     *          200="Returned when successful"
     *     },
     *     headers={{"name"="Authorization", "required"=true,"description"="Bearer token"}}
     * )
     */
    //  Keep this method declaration before getOrderAction($id) for FOSRestBundle to generate the routes with
    // the correct priority
    public function getOrdersOpenAction()
    {
        $em = $this->getDoctrine()->getManager();

        /** @var $poRepository PurchaseOrderRepository */
        $poRepository = $em->getRepository('LogisticsBundle:PurchaseOrder');
        $queryBuilder = $poRepository->prepareBaseStatement();
        $queryBuilder->where('po.processedDate is null');

        $result = $queryBuilder->getQuery()->getResult();

        $serializer = $this->get('jms_serializer');
        return new Response($serializer->serialize($result, 'json'));
    }

    /**
     * GET the list of closed purchase orders
     *
     * @return JsonResponse containing the list of purchase orders where the processedDate is not null
     *
     *
     * @ApiDoc(
     *     resource="Operations on purchase orders.",
     *     resourceDescription="Operations on purchase orders.",
     *     section="Orders",
     *     description="List all closed purchase orders.",
     *     statusCodes={
     *          200="Returned when successful"
     *     },
     *     headers={{"name"="Authorization", "required"=true,"description"="Bearer token"}}
     * )
     */
    //  Keep this method declaration before getOrderAction($id) for FOSRestBundle to generate the routes with
    // the correct priority
    public function getOrdersClosedAction()
    {
        $em = $this->getDoctrine()->getManager();

        /** @var $poRepository PurchaseOrderRepository */
        $poRepository = $em->getRepository('LogisticsBundle:PurchaseOrder');
        $queryBuilder = $poRepository->prepareBaseStatement();
        $queryBuilder->where('po.processedDate is not null');

        $result = $queryBuilder->getQuery()->getResult();

        $serializer = $this->get('jms_serializer');
        return new Response($serializer->serialize($result, 'json'));
    }

    /**
     * GET all quantities remaining to be assigned in opened purchase orders, grouped by reference name
     *
     * @return JsonResponse containing the list of purchase orders where the processedDate is null
     *
     *
     * @ApiDoc(
     *     resource="Operations on purchase orders.",
     *     resourceDescription="Operations on purchase orders.",
     *     section="Orders",
     *     description="List open quantities per reference name in open orders.",
     *     statusCodes={
     *          200="Returned when successful"
     *     },
     *     headers={{"name"="Authorization", "required"=true,"description"="Bearer token"}}
     * )
     */
    public function getOrdersOpenquantitiesAction()
    {
        $em = $this->getDoctrine()->getManager();

        /** @var $poRepository PurchaseOrderRepository */
        $poRepository = $em->getRepository('LogisticsBundle:PurchaseOrder');
        $queryBuilder = $poRepository->prepareBaseStatement();
        $queryBuilder->where('po.processedDate is null');

        $result = $queryBuilder->getQuery()->getResult();

        $summary = [];
        /** @var $order PurchaseOrder */
        foreach ($result as $order) {
            /** @var $orderLine OrderDetail */
            foreach ($order->getOrderDetails() as $orderLine)
            {
                if (empty($orderLine->getInventoryItem()))
                {
                    $refName = $orderLine->getCatalogItem()->getRefName();
                    if (array_key_exists($refName, $summary)) {
                        $summary[$refName]['quantity'] += 1;
                    } else {
                        $summary[$refName] = array('refName' => $refName, 'quantity' => 1);
                    }
                }
            }
        }
        ksort($summary);

        $serializer = $this->get('jms_serializer');
        return new Response($serializer->serialize(array_values($summary), 'json'));
    }


    /**
     * GET the purchase order whose ID is passed as a parameter including the
     * related distributor information and order details
     *
     * @param integer $id ID of the purchase order to be retrieved
     * @return Response (JSON)
     *
     * @ApiDoc(
     *     resource="Operations on purchase orders.",
     *     resourceDescription="Operations on purchase orders.",
     *     section="Orders",
     *     description="Retrieve a purchase orders from its ID.",
     *     statusCodes={
     *          200="Returned when successful"
     *     },
     *     headers={{"name"="Authorization", "required"=true,"description"="Bearer token"}}
     * )
     */
    public function getOrderAction($id)
    {
        $po = $this->getDoctrine()->getManager()
            ->getRepository('LogisticsBundle:PurchaseOrder')
            ->findOneById($id);

        $serializer = $this->get('jms_serializer');
        return new Response($serializer->serialize($po, 'json'));
    }

    /**
     * GET all orders
     *
     * @return Response JSON response
     *
     *
     * @ApiDoc(
     *     resource="Operations on purchase orders.",
     *     resourceDescription="Operations on purchase orders.",
     *     section="Orders",
     *     description="List all purchase orders.",
     *     statusCodes={
     *          200="Returned when successful"
     *     },
     *     headers={{"name"="Authorization", "required"=true,"description"="Bearer token"}}
     * )
     */
    public function getOrdersAction(){
        $em = $this->getDoctrine()->getManager();

        /** @var $poRepository PurchaseOrderRepository */
        $poRepository = $em->getRepository('LogisticsBundle:PurchaseOrder');
        $queryBuilder = $poRepository->prepareBaseStatement();

        $result = $queryBuilder->getQuery()->getResult();

        $serializer = $this->get('jms_serializer');
        $response = new Response($serializer->serialize($result, 'json'));


        return $response;
    }

//    public function putOrderProcesseddateAction($id)
//    {
//        $em = $this->getDoctrine()->getManager();
//
//        /** @var $poRepository PurchaseOrderRepository */
//        $poRepository = $em->getRepository();
//
//        /** @var $purchaseOrder PurchaseOrder */
//        $purchaseOrder = $poRepository->findOneBy($id);
//        $purchaseOrder->setProcessedDate(new \DateTime());
//        $em->persist($purchaseOrder);
//        $em->flush();
//
//        $serializer = $this->get('jms_serializer');
//        $response = new Response($serializer->serialize($purchaseOrder, 'json'));
//
//        return $response;
//
//    }

    /**
     * Updates all orderDetails of the order whose id is apassed as a parameter with
     * the deliveryNumber
     * Also updates the order processedDate with the current date
     *
     * @param integer $id ID of the purchase order to be updated
     * @param string $deliveryNumber Delivery number to set on all orderDetails
     * @return Response updated Order
     *
     *
     * @ApiDoc(
     *     resource="Operations on purchase orders.",
     *     resourceDescription="Operations on purchase orders.",
     *     section="Orders",
     *     description="Updates a purchase order with a delivery number.",
     *     statusCodes={
     *          200="Returned when successful"
     *     },
     *     headers={{"name"="Authorization", "required"=true,"description"="Bearer token"}}
     * )
     */
    public function putOrderDeliverynumberAction($id, $deliveryNumber)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var $poRepository PurchaseOrderRepository */
        $poRepository = $em->getRepository('LogisticsBundle:PurchaseOrder');

        /** @var $purchaseOrder PurchaseOrder */
        $purchaseOrder = $poRepository->findOneById($id);
        $purchaseOrder->setProcessedDate(new \DateTime());
//        $purchaseOrder->setDeliveryNumber($deliveryNumber);
        $em->persist($purchaseOrder);
        $em->flush();

        /** @var $queryBuilder QueryBuilder */
        $queryBuilder = $em->createQueryBuilder();
        $queryBuilder->update('LogisticsBundle:OrderDetail', 'od')
            ->set('od.deliveryNumber', ':deliveryNumber')
            ->where('od.purchaseOrder = :id')
            ->setParameter('deliveryNumber', $deliveryNumber)
            ->setParameter('id', $id);
        $queryBuilder->getQuery()->execute();

        $serializer = $this->get('jms_serializer');
        $response = new Response($serializer->serialize($purchaseOrder, 'json'));

        return $response;

    }

    /**
     * Updates the lastUpdateUser of the order whose ID is passed as a parameter with
     * the User corresponding to the username parameter
     *
     * @param integer $id ID of the Order to be updated
     * @return Response updated Order
     *
     * @ApiDoc(
     *     resource="Operations on purchase orders.",
     *     resourceDescription="Operations on purchase orders.",
     *     section="Orders",
     *     description="Updates a purchase order with the current user.",
     *     statusCodes={
     *          200="Returned when successful"
     *     },
     *     headers={{"name"="Authorization", "required"=true, "description"="Bearer token"}}
     * )
     */
    public function putOrderUserAction($id)
    {
        $username = $this->get('security.token_storage')->getToken()->getUser()->__toString();

        $em = $this->getDoctrine()->getManager();

        /** @var $orderManager OrderManager */
        $orderManager = $this->get('logistics.order_manager');

        /** @var $purchaseOrder PurchaseOrder */
        $purchaseOrder = $orderManager->assignUserToOrder($username, $id);
        $em->persist($purchaseOrder);
        $em->flush();

        $serializer = $this->get('jms_serializer');
        $response = new Response($serializer->serialize($purchaseOrder, 'json'));

        return $response;

    }

}