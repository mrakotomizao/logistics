<?php

namespace LogisticsBundle\Controller;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\Serializer;
use LogisticsBundle\Entity\InventoryItem;
use LogisticsBundle\Model\DeviceManager;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DevicesController extends Controller
{

    /**
     * Returns the list of devices (InventoryItems) in error in JSON format
     *
     * @return Response
     *
     * @Security("has_role('ROLE_ADMIN')")
     *
     * @ApiDoc(
     *     resource="Operations on devices.",
     *     resourceDescription="Operations on devices.",
     *     section="Devices",
     *     description="List all devices in error.",
     *     statusCodes={
     *          200="Returned when successful"
     *     },
     *     headers={{"name"="Authorization", "required"=true,"description"="Bearer token"}},
     *     responseMap={}
     * )
     */
    public function getDevicesErrorAction()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("LogisticsBundle:InventoryItem");

        $devices = $repository->findByValid(false);

        $serializer = $this->get('jms_serializer');
        $response = new Response($serializer->serialize($devices, 'json'));

        return $response;
    }

    /**
     * Retrieves a single device (InventoryItem) information for the device
     * whose serialNumber is passed as a parameter.
     *
     * @param string $serialNumber Serial number of the device for which to retrieve information.
     * @return Response
     *
     * @ApiDoc(
     *     resource="Operations on devices.",
     *     resourceDescription="Operations on devices.",
     *     section="Devices",
     *     description="Retrieves a device information.",
     *     statusCodes={
     *          200="Returned when successful"
     *     },
     *     headers={{"name"="Authorization", "required"=true,"description"="Bearer token"}}
     * )
     */
    public function getDeviceAction($serialNumber)
    {
        $em = $this->getDoctrine()->getManager();

        $invItemRepository = $em->getRepository('LogisticsBundle:InventoryItem');
        $invItem = $invItemRepository->findOneBySerialNumber($serialNumber);

        if (!empty($invItem)) {
            $response_code = Response::HTTP_OK;
        } else {
            $response_code = Response::HTTP_BAD_REQUEST;
        }

        $context = new SerializationContext();
        $context->setSerializeNull(true);
        /** @var $serializer Serializer */
        $serializer = $this->get('jms_serializer');
        $response = new Response($serializer->serialize($invItem, 'json', $context), $response_code);

        return $response;
    }

    /**
     * Check whether the device whose serial number is passed as a parameter is valid for both SSE and Objenious.
     * Also update the device as invalid if the check fails.
     *
     * @param string $serialNumber Serial number of the device to be checked
     * @return JsonResponse
     *
     * @ApiDoc(
     *     resource="Operations on devices.",
     *     resourceDescription="Operations on devices.",
     *     section="Devices",
     *     description="Check if a device is valid for SSE and Objenious.",
     *     statusCodes={
     *          200="Returned when successful",
     *          400="Returned when device is not found"
     *     },
     *     headers={{"name"="Authorization", "required"=true,"description"="Bearer token"}}
     * )
     */
    public function getDeviceStateAction($serialNumber)
    {
        /** @var $idResponse Response */
        $idResponse = $this->getDeviceObjeniousidAction($serialNumber);
        if ($idResponse->getStatusCode() == Response::HTTP_OK) {
            $objResponse = $this->getDeviceObjeniousstateAction($serialNumber);
            $sseResponse = $this->getDeviceSsestateAction($serialNumber);
        }

        $em = $this->getDoctrine()->getManager();
        $invItemRepository = $em->getRepository("LogisticsBundle:InventoryItem");

        if ($idResponse->getStatusCode() == Response::HTTP_OK
            && $objResponse->getStatusCode() == Response::HTTP_OK
            && $sseResponse->getStatusCode() == Response::HTTP_OK) {
            /** @var $deviceManager DeviceManager */
            $deviceManager = $this->get('logistics.device_manager');
            $inventoryItem = $deviceManager->setCheckSuccessful($serialNumber);
            $em->persist($inventoryItem);
            $em->flush();
        } else {
            $inventoryItem = $invItemRepository->findOneBySerialNumber($serialNumber);
        }

        if ($inventoryItem != null) {
            $response = array("serialNumber" => $serialNumber,
                "objeniousId" => $inventoryItem->getObjeniousId(),
                "isValid" => $inventoryItem->isValid());
            if (!$inventoryItem->isValid()) {
                $response["error"] = json_decode($inventoryItem->getError());
            };
            $response_code = Response::HTTP_OK;
        } else {
            $response["error"] = "Device not found.";
            $response_code = Response::HTTP_BAD_REQUEST;
        }

        return new JsonResponse($response, $response_code);

    }

    /**
     * Get Objenious internal id
     *
     * @param $serialNumber - Device Serial Number
     * @return Response -
     *
     * Example :
     * [
     * {
     * "id": 3940649673949570,
     * "label": "FLUDIA-0F41",
     * "link": "https://api.objenious.com/v1/devices/3940649673949570",
     * "profile": {
     * "id": 2,
     * "link": "https://api.objenious.com/v1/profiles/2"
     * },
     * "group": {
     * "id": 2,
     * "link": "https://api.objenious.com/v1/groups/2"
     * },
     * "status": "active",
     * "properties": {
     * "external_id": null,
     * "deveui": "70B3D59BA0000F41",
     * "appeui": "70B3D59BA0000004"
     * }
     * }
     * ]
     */
    public function getDeviceObjeniousidAction($serialNumber)
    {
        $curl = curl_init();
        // M_EXTERNAL_ID (Objenious internal id) is going
        curl_setopt($curl, CURLOPT_URL, "https://api.objenious.com/v1/devices?deveui=" . $serialNumber . "");

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ["Content-Type: application/json", "apikey: " . $this->getParameter("apikey")]);

        $result = curl_exec($curl);

        curl_close($curl);

        /** @var $deviceManager DeviceManager */
        $deviceManager = $this->get('logistics.device_manager');
        $data = json_decode($result, true);
        if (empty($data)) {
            $error = "Not registered at Objenious";
            $response = array("serialNumber" => $serialNumber, "error" => $error);
            $responseCode = Response::HTTP_BAD_REQUEST;

            $inventoryItem = $deviceManager->setCheckFailed($serialNumber, $error);
        } else {
            $objeniousId = $data[0]["id"];
            $response = array("serialNumber" => $serialNumber, "objeniousId" => $objeniousId);
            $responseCode = Response::HTTP_OK;

            $inventoryItem = $deviceManager->setObjeniousId($serialNumber, $objeniousId);
        }

        // Update the inventory item depending on the check's result
        if ($inventoryItem) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($inventoryItem);
            $em->flush();
        }

        return new JsonResponse($response, $responseCode);
    }

    /**
     * Get Device state
     *
     * @param $serialNumber Logistics Serial Number
     * @return Response -
     *  Example :
     * {
     * "id": 3940649673949417,
     * "status": "active",
     * "uplink_count": 5593,
     * "last_uplink": "2017-08-28T15:24:06.358Z",
     * "downlink_count": 0,
     * "data": {},
     * "lat": 48.868753535378,
     * "lng": 2.3222700347315,
     * "geolocation_type": "network"
     * }
     */
    public function getDeviceObjeniousstateAction($serialNumber)
    {
        $em = $this->getDoctrine()->getManager();
        $inventoryItemRepository = $em->getRepository('LogisticsBundle:InventoryItem');

        /** @var $itemToCheck InventoryItem */
        $itemToCheck = $inventoryItemRepository->findOneBySerialNumber($serialNumber);
        $objeniousId = $itemToCheck->getObjeniousId();

        $curl = curl_init();
        // M_EXTERNAL_ID (Objenious internal id) is going
        curl_setopt($curl, CURLOPT_URL, "https://api.objenious.com/v1/devices/" . $objeniousId . "/state");

        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ["Content-Type: application/json", "apikey: " . $this->getParameter("apikey")]);

        $result = curl_exec($curl);

        curl_close($curl);

        $data = json_decode($result, true);

        // If the response from Objenious is empty the sensor is considered as invalid
        // Otherwise, the status must be active and the device must have received at least
        // 1 uplink to be valid
        if (empty($data)) {
            $isValid = false;
        } else {
            $isValid = $data["status"] === 'active' && $data["uplink_count"] >= 1;
        }

        $response = array("serialNumber" => $serialNumber,
            "objeniousId" => $objeniousId,
            "isValid" => $isValid);

        if (!$isValid) {
            $error = "Not functional at Objenious";
            $response["error"] = $error;
            $responseCode = Response::HTTP_BAD_REQUEST;

            /** @var $deviceManager DeviceManager */
            $deviceManager = $this->get('logistics.device_manager');
            $inventoryItem = $deviceManager->setCheckFailed($serialNumber, $error);

            $em->persist($inventoryItem);
            $em->flush();
        } else {
            $responseCode = Response::HTTP_OK;
        }

        return new JsonResponse($response, $responseCode);
    }

    /**
     * Get Vertx Api access token
     * Get System -> Know if exist in SSE , give external ID if exist
     *
     * @param $serialNumber - Device Serial Number (deveui)
     * @return Response - Vertx getSystem response
     */
    public function getDeviceSsestateAction($serialNumber)
    {
        $vertxToken = $this->getVertxApiToken($this->getParameter("usernameVertx"), $this->getParameter("passwordVertx"));

        $em = $this->getDoctrine()->getManager();

        $curl = curl_init();
        // M_EXTERNAL_ID (Objenious internal id) is going
        curl_setopt($curl, CURLOPT_URL, $this->getParameter("vertxUrl") . "/api/v1/systems/" . $serialNumber);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ["Content-Type: application/json", "Authorization: Bearer " . $vertxToken . ""]);

        $result = curl_exec($curl);

        curl_close($curl);

        $data = json_decode($result, true);

        // If the response from Vertex is empty the sensor is considered as invalid
        // Otherwise, the device is considered as valid if the system information is returned
        if (empty($data)) {
            $isValid = false;
        } else {
            $isValid = array_key_exists('system', $data);
        }

        $response = array("serialNumber" => $serialNumber,
            "isValid" => $isValid);

        if (!$isValid) {
            $error = "Not functional at SSE";
            $response["error"] = $error;
            $responseCode = Response::HTTP_BAD_REQUEST;

            /** @var $deviceManager DeviceManager */
            $deviceManager = $this->get('logistics.device_manager');
            $inventoryItem = $deviceManager->setCheckFailed($serialNumber, $error);

            $em->persist($inventoryItem);
            $em->flush();
        } else {
            $responseCode = Response::HTTP_OK;
        }

        return new JsonResponse($response, $responseCode);
    }

    /**
     * Get Vertx API token
     *
     * Need an account on prod DB
     * @param $username
     * @param $password
     * @return mixed - vertx token
     */
    public function getVertxApiToken($username, $password)
    {
        $data = ["email" => base64_encode($username), "password" => base64_encode($password)];
        $data = ["user" => $data];
        $data_str = json_encode($data);
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $this->getParameter("vertxUrl") . "/api/authenticate");
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data_str);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

        $result = curl_exec($curl);
        $result = json_decode($result);
        curl_close($curl);
        return $result->access_token;
    }

    public function postInventoryitemsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $itemRepository = $em->getRepository('LogisticsBundle:CatalogItem');
        $content = $request->getContent();

        $data = json_decode($content, true);

        $batchSize = 25;
        $count = 0;

        foreach ($data as $itemToCreate) {
            $inventoryItem = new InventoryItem();
            $catalogItem = $itemRepository->findOneByRefName($itemToCreate['ref_name']);
            if (empty($catalogItem)) {
                \Doctrine\Common\Util\Debug::dump($itemToCreate['ref_name'] . '|');
            }
            $inventoryItem->setCatalogItem($catalogItem);
            $inventoryItem->setSerialNumber($itemToCreate['serial_number']);

            $em->persist($inventoryItem);
            $count++;
            if (($count % $batchSize) == 0 or count($data) == $count) {
                $em->flush();
                $em->clear();
            }
        }

        $response = new Response();
        $response->setStatusCode(Response::HTTP_CREATED);
        return $response;
    }

    /**
     * For testing purposes
     * Returns the list of InventoryItems for a given reference passed as a parameter
     *
     * @param $refName
     * @return Response (JSON)
     */
    // TODO Remove ?
    public function getInventoryitemsAction($refName)
    {
        $em = $this->getDoctrine()->getManager();
        $invItemRepository = $em->getRepository('LogisticsBundle:InventoryItem');
        $catItemRepository = $em->getRepository('LogisticsBundle:CatalogItem');

        $catItems = $catItemRepository->findByRefName($refName);

        $result = $invItemRepository->findByCatalogItem($catItems);

        $serializer = $this->get('jms_serializer');
        $response = new Response($serializer->serialize($result, 'json'));

        return $response;
    }
}