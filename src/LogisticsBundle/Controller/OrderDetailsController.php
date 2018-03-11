<?php

namespace LogisticsBundle\Controller;

use LogisticsBundle\Model\DeviceManager;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class OrderDetailsController
 * @package LogisticsBundle\Controller
 */
class OrderDetailsController extends Controller
{

    /**
     * Assign an device (InventoryItem) to the order detail whose id
     * is provided as a parameter. Also update the device as successfully checked.
     *
     * @param $orderDetailId id of the orderDetail to assign the device to
     * @param $serialNumber serialNumber of the InventoryItem to be assigned
     * @return JsonResponse updated orderDetail
     *
     * @ApiDoc(
     *     resource="Operations on order lines.",
     *     resourceDescription="Operations on order lines.",
     *     section="Order details",
     *     description="Assign a device to an order line.",
     *     statusCodes={
     *          200="Returned when successful"
     *     },
     *     headers={{"name"="Authorization", "required"=true,"description"="Bearer token"}}
     * )
     */
    public function putOrderdetailsDeviceAction($orderDetailId, $serialNumber)
    {
        $em = $this->getDoctrine()->getManager();

        /** @var $deviceManager DeviceManager */
        $deviceManager = $this->get('logistics.device_manager');

        $orderDetail = $deviceManager->assignDeviceToOrderDetail($serialNumber, $orderDetailId);
        $em->persist($orderDetail);
        $em->flush();

        return new JsonResponse($orderDetail);
    }
}