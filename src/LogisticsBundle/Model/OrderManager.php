<?php

namespace LogisticsBundle\Model;

use AuthBundle\Entity\User;
use AuthBundle\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use LogisticsBundle\Entity\CatalogItem;
use LogisticsBundle\Entity\CatalogPack;
use LogisticsBundle\Entity\ErroredOrder;
use LogisticsBundle\Entity\PurchaseOrder;
use LogisticsBundle\Repository\CatalogItemRepository;
use LogisticsBundle\Repository\CatalogPackRepository;
use LogisticsBundle\Entity\OrderDetail;
use LogisticsBundle\Repository\DistributorRepository;
use LogisticsBundle\Repository\PurchaseOrderRepository;

class OrderManager
{

    private $catalogPackRepository;
    private $catalogItemRepository;
    private $distributorRepository;
    private $purchaseOrderRepository;
    private $userRepository;

    const DEFAULT_GROUP_NUM = 0;
    const DEFAULT_GROUP_NAME = 'Default';

    /**
     * OrderManager constructor.
     * @param CatalogPackRepository $catalogPackRepository
     * @param CatalogItemRepository $catalogItemRepository
     * @param DistributorRepository $distributorRepository
     */
    public function __construct(CatalogPackRepository $catalogPackRepository,
                                CatalogItemRepository $catalogItemRepository,
                                DistributorRepository $distributorRepository,
                                PurchaseOrderRepository $purchaseOrderRepository,
                                UserRepository $userRepository)
    {
        $this->catalogPackRepository = $catalogPackRepository;
        $this->catalogItemRepository = $catalogItemRepository;
        $this->distributorRepository = $distributorRepository;
        $this->purchaseOrderRepository = $purchaseOrderRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * Takes a CatalogPack as a parameter and returns a list of OrderDetails (one per individual item in the pack)
     *
     * @param CatalogPack $catalogPack
     * @return array|ArrayCollection
     */
    public function getOrderDetailsFromCatalogPack($ean, $quantity, $group_number)
    {
        /** @var $catalogPack CatalogPack */
        $catalogPack = $this->catalogPackRepository->findOneByEan($ean);
        $orderDetails = new ArrayCollection();

        if ($catalogPack !== null)
        {
            for ($qty = 0; $qty < $quantity; $qty++)
            {
                foreach ($catalogPack->getCatalogPackDetails() as $packDetail)
                {
                    for ($i = 0; $i < ($packDetail->getQuantity()); $i++){
                        $orderDetail = new OrderDetail();
                        $orderDetail->setCatalogItem($packDetail->getCatalogItem());
                        $orderDetail->setGroupNumber($group_number);
                        $orderDetail->setGroupDescription($catalogPack->getDescription());
                        $orderDetail->setOrderedEan($ean);
                        $orderDetails[] = $orderDetail;
                    }
                }
                $group_number++;
            }

        }

        return $orderDetails;
    }

    /**
     * Takes a CatalogItem as a parameter and returns a list of OrderDetails (one per individual item in the pack)
     *
     * @param CatalogItem $catalogItem
     * @return array|ArrayCollection
     */
    public function getOrderDetailsFromCatalogItem($ean, $quantity)
    {
        $catalogItem = $this->catalogItemRepository->findOneByEan($ean);
        $orderDetails = new ArrayCollection();

        if ($catalogItem !== null)
        {
            for ($qty = 0; $qty < $quantity; $qty++) {
                $orderDetail = new OrderDetail();
                $orderDetail->setCatalogItem($catalogItem);
                $orderDetail->setGroupNumber(self::DEFAULT_GROUP_NUM);
                $orderDetail->setGroupDescription(self::DEFAULT_GROUP_NAME);
                $orderDetail->setOrderedEan($ean);
                $orderDetails[] = $orderDetail;
            }
        }

        return $orderDetails;
    }

//    /**
//     * Retrieve OrderDetails to be added to a PurchaseOrder from the refName of a CatalogItem or CatalogPack
//     * @param $refName
//     * @return array|ArrayCollection
//     */
//    public function getOrderDetailsFromRefName($refName)
//    {
//        $catalogPack = $this->catalogPackRepository->findOneByRefName($refName);
//
//        $catalogItem = $this->catalogItemRepository->findOneByRefName($refName);
//
//        $orderDetails = new ArrayCollection();
//
//        if ($catalogPack !== null and $catalogItem === null) {
//            $orderDetails = $this->getOrderDetailsFromCatalogPack($catalogPack);
//        }
//        if ($catalogPack === null and $catalogItem !== null) {
//            $orderDetails = $this->getOrderDetailsFromCatalogItem($catalogItem);
//        }
//
//        return $orderDetails;
//    }

    /**
     * @param array $jsonData
     * @return ArrayCollection
     */
    public function decodeFromJson(array $jsonData)
    {
        $orders = new ArrayCollection();
        foreach ($jsonData['orders'] as $order)
        {
            $purchaseOrder = new PurchaseOrder();
            $orderHeader = $order['header'];
            $purchaseOrder->setClientCode($orderHeader['advizeo_client_code']);
            $purchaseOrder->setClientName($orderHeader['company']);
            $purchaseOrder->setContactEmail($orderHeader['email']);
            $purchaseOrder->setContactName($orderHeader['contact']);
            $purchaseOrder->setContactNumber($orderHeader['phone']);
            $purchaseOrder->setAddressLine1($orderHeader['address_1']);
            $purchaseOrder->setAddressLine2($orderHeader['address_2']);
            $purchaseOrder->setZip($orderHeader['zip']);
            $purchaseOrder->setCity($orderHeader['city']);
            $purchaseOrder->setOrderDate(new \DateTime($orderHeader['order_date']));
            $purchaseOrder->setSource($orderHeader['order_origin']);
            $purchaseOrder->setSourceOrderNum($orderHeader['order_id']);

            $erroredOrder = new ErroredOrder();
            $erroredOrder->setSource($purchaseOrder->getSource());
            $erroredOrder->setSourceOrderNum($purchaseOrder->getSourceOrderNum());
            $erroredOrder->setOrderMessage(json_encode($order));
            $errorMessages = [];

            $distributor = $this->distributorRepository->findOneByCode($orderHeader['logistic_provider_code']);
            if ($distributor != null)
            {
                $purchaseOrder->setDistributor($distributor);
            } else
            {
                $errorMessages[] = "Unknown distributor";
            }

            // Provides a group number > 0 for each individual pack within the purchase order
            $group_num = 1;
            // Translate products_details from JSON to order_details to be stored (convert packs to item lines)
            foreach ($order['product_details'] as $orderLine)
            {
                $catalogEan = $orderLine['ean'];

                // Retrieves the orderDetails list from the pack definition if the product line is a pack
                // Retrieves the orderDetails list from the item definition otherwise
                if ($this->isPack($catalogEan))
                {
                    $orderDetails = $this->getOrderDetailsFromCatalogPack($catalogEan, $orderLine['quantity'], $group_num);
                    $group_num += $orderLine['quantity'];
                } else
                {
                    $orderDetails = $this->getOrderDetailsFromCatalogItem($catalogEan, $orderLine['quantity']);
                }

                if (!$orderDetails->isEmpty())
                {
                    $purchaseOrder->addOrderDetailsArray($orderDetails);
                } else
                {
                    $errorMessages[] = "Ean " . $catalogEan . " not found in catalog";
                }

            }
            if (empty($errorMessages))
            {
                $orders[] = $purchaseOrder;
            } else
            {
                $erroredOrder->setErrorMessages(json_encode($errorMessages));
                $orders[] = $erroredOrder;
            }
        }

        return $orders;
    }


    public function assignUserToOrder($username, $orderId)
    {
        /** @var $purchaseOrder PurchaseOrder */
        $purchaseOrder = $this->purchaseOrderRepository->findOneById($orderId);

        /** @var $user User */
        $user = $this->userRepository->findOneByUsername($username);

        $purchaseOrder->setLastUpdateUser($user);
        if (!$purchaseOrder->getStartProcessingDate()) {
            $purchaseOrder->setStartProcessingDate(new \DateTime());
        }

        return $purchaseOrder;
    }

    public function isPack($ean)
    {
        $result = $this->catalogPackRepository->findOneByEan($ean);
        if (!empty($result))
        {
            return true;
        } else {
            $result = $this->catalogItemRepository->findOneByEan($ean);
            if (!empty($result))
            {
                return false;
            }
        }
        return false;
    }

}