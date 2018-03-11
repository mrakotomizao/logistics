<?php

namespace LogisticsBundle\Model;

use LogisticsBundle\Entity\InventoryItem;
use LogisticsBundle\Entity\OrderDetail;
use LogisticsBundle\Repository\InventoryItemRepository;
use LogisticsBundle\Repository\OrderDetailRepository;
use Symfony\Component\Validator\Constraints\DateTime;

class DeviceManager
{

    private $inventoryItemRepository;
    private $orderDetailRepository;

    /**
     * DeviceManager constructor.
     * @param InventoryItemRepository $inventoryItemRepository
     */
    public function __construct(InventoryItemRepository $inventoryItemRepository,
                                OrderDetailRepository $orderDetailRepository)
    {
        $this->inventoryItemRepository = $inventoryItemRepository;
        $this->orderDetailRepository = $orderDetailRepository;
    }

    public function setObjeniousId($serialNumber, $objeniousId)
    {
        /** @var $inventoryItem InventoryItem */
        $inventoryItem = $this->inventoryItemRepository->findOneBySerialNumber($serialNumber);

        $inventoryItem->setObjeniousId($objeniousId);

        return $inventoryItem;
    }

    public function setCheckSuccessful($serialNumber)
    {
        /** @var $inventoryItem InventoryItem */
        $inventoryItem = $this->inventoryItemRepository->findOneBySerialNumber($serialNumber);

        $inventoryItem->setCheckedDate(new \DateTime());
        $inventoryItem->setValid(true);
        $inventoryItem->setError(null);

        return $inventoryItem;
    }

    public function setCheckFailed($serialNumber, $error)
    {
        /** @var $inventoryItem InventoryItem */
        $inventoryItem = $this->inventoryItemRepository->findOneBySerialNumber($serialNumber);

        if ($inventoryItem)
        {
            $inventoryItem->setCheckedDate(new \DateTime());
            $inventoryItem->setValid(false);
            $errors = json_decode($inventoryItem->getError());
            if (empty($errors) || !in_array($error, $errors))
            {
                $errors[] = $error;
            }
            $inventoryItem->setError(json_encode($errors));
        }

        return $inventoryItem;
    }

    public function assignDeviceToOrderDetail($serialNumber, $orderDetailId)
    {
        /** @var $orderDetail OrderDetail */
        $orderDetail = $this->orderDetailRepository->findOneById($orderDetailId);

        /** @var $inventoryItem InventoryItem */
        $inventoryItem = $this->setCheckSuccessful($serialNumber);
        $orderDetail->setInventoryItem($inventoryItem);

        return $orderDetail;
    }

}