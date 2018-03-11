<?php

namespace LogisticsBundle\Controller;

use Doctrine\ORM\AbstractQuery;
use LogisticsBundle\Entity\InventoryItem;
use LogisticsBundle\Entity\PackRegistration;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class PackRegistrationsController
 * @package LogisticsBundle\Controller
 */
class PackRegistrationsController extends Controller
{

    /**
     * POST the relations ships between a pack serial number and the serial numbers of
     * the devices included in the pack.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function postPackregistrationAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $invItemRepository = $em->getRepository('LogisticsBundle:InventoryItem');

        $content = $request->getContent();

        $data = json_decode($content, true);

        $packSerial = $data['packSerial'];

        foreach ($data['deviceSerials'] as $deviceSerial) {
            /** @var $inventoryItem InventoryItem */
            $inventoryItem = $invItemRepository->findOneBySerialNumber($deviceSerial);
            $inventoryItem->setPackSerial($packSerial);

            $em->persist($inventoryItem);
        }
        $em->flush();
        $em->clear();

        $response = new Response();

        return $response;
    }

    /**
     * Lists all devices serial numbers (devEUI) registered to the packSerial provided in parameter
     *
     * @param $packSerial
     * @return Response
     */
    public function getPackregistrationsAction($packSerial) {
        $em = $this->getDoctrine()->getManager();
//        $packRegistrationRepository = $em->getRepository("LogisticsBundle:PackRegistration");
//
//        $packRegistrations = $packRegistrationRepository->findByPackSerial($packSerial);

        $itemRepository = $em->getRepository("LogisticsBundle:InventoryItem");
        $items = $itemRepository->findByPackSerial($packSerial);

        $packRegistrationsMessage = [];
        if (count($items) > 0) {
            $packRegistrationsMessage["packSerial"] = $packSerial;
            $packRegistrationsMessage["devEUI"] = [];
            /** @var $item InventoryItem */
            foreach ($items as $item)
            {
                $packRegistrationsMessage["devEUI"][] = $item->getSerialNumber();
            }

        }


        $serializer = $this->get('jms_serializer');
        $response = new Response($serializer->serialize($packRegistrationsMessage, 'json'));

        return $response;

    }

}