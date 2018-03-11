<?php

namespace LogisticsBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;
use LogisticsBundle\Entity\OrderDetail;
use LogisticsBundle\Repository\OrderDetailRepository;
use LogisticsBundle\Model\DeviceManager;

class OrderDetailManager
{

    private $orderDetailRepository;
    private $deviceManager;

    /**
     * OrderDetailManager constructor.
     * @param OrderDetailRepository $orderDetailRepository
     * @param \LogisticsBundle\Model\DeviceManager $deviceManager
     */
    public function __construct(OrderDetailRepository $orderDetailRepository, DeviceManager $deviceManager)
    {
        $this->orderDetailRepository = $orderDetailRepository;
        $this->deviceManager = $deviceManager;
    }

}