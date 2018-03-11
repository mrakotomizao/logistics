<?php

namespace LogisticsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * OrderDetail
 *
 * @ORM\Table(name="order_detail")
 * @ORM\Entity(repositoryClass="LogisticsBundle\Repository\OrderDetailRepository")
 */
class OrderDetail
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var PurchaseOrder
     *
     * @ORM\ManyToOne(targetEntity="LogisticsBundle\Entity\PurchaseOrder", inversedBy="orderDetails")
     * @ORM\JoinColumn(nullable=false)
     */
    private $purchaseOrder;

    /**
     * @var string
     *
     * @ORM\Column(name="delivery_number", type="string", length=25, nullable=true)
     */
    private $deliveryNumber;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="delivery_date", type="datetime", nullable=true)
     */
    private $deliveryDate;

    /**
     * @var int
     *
     * @ORM\Column(name="group_number", type="integer")
     */
    private $groupNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="group_description", type="string", length=255, nullable=true)
     */
    private $groupDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="ordered_ean", type="string", length=255, nullable=true)
     */
    private $orderedEan;

    /**
     * @var CatalogItem
     *
     * @ORM\ManyToOne(targetEntity="LogisticsBundle\Entity\CatalogItem")
     * @ORM\JoinColumn(nullable=false)
     */
    private $catalogItem;

    /**
     * @var InventoryItem $inventoryItem
     *
     * @ORM\ManyToOne(targetEntity="LogisticsBundle\Entity\InventoryItem", cascade={"persist"})
     */
    private $inventoryItem;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return PurchaseOrder
     */
    public function getPurchaseOrder()
    {
        return $this->purchaseOrder;
    }

    /**
     * @param PurchaseOrder $purchaseOrder
     *
     * @return OrderDetail
     */
    public function setPurchaseOrder($purchaseOrder)
    {
        $this->purchaseOrder = $purchaseOrder;

        return $this;
    }

    /**
     * Get DeliveryNumber
     *
     * @return string
     */
    public function getDeliveryNumber()
    {
        return $this->deliveryNumber;
    }

    /**
     * Set DeliveryNumber
     *
     * @param string $deliveryNumber
     * @return OrderDetail
     */
    public function setDeliveryNumber($deliveryNumber)
    {
        $this->deliveryNumber = $deliveryNumber;
        return $this;
    }

    /**
     * Get deliveryDate
     *
     * @return \DateTime
     */
    public function getDeliveryDate()
    {
        return $this->deliveryDate;
    }

    /**
     * Set deliveryDate
     *
     * @param \DateTime $deliveryDate
     *
     * @return OrderDetail
     */
    public function setDeliveryDate($deliveryDate)
    {
        $this->deliveryDate = $deliveryDate;

        return $this;
    }

    /**
     * Set groupNumber
     *
     * @param integer $groupNumber
     *
     * @return OrderDetail
     */
    public function setGroupNumber($groupNumber)
    {
        $this->groupNumber = $groupNumber;

        return $this;
    }

    /**
     * Get groupNumber
     *
     * @return int
     */
    public function getGroupNumber()
    {
        return $this->groupNumber;
    }

    /**
     * Set groupDescription
     *
     * @param string $groupDescription
     *
     * @return OrderDetail
     */
    public function setGroupDescription($groupDescription)
    {
        $this->groupDescription = $groupDescription;

        return $this;
    }

    /**
     * Get groupDescription
     *
     * @return string
     */
    public function getGroupDescription()
    {
        return $this->groupDescription;
    }

    /**
     * @return CatalogItem
     */
    public function getCatalogItem()
    {
        return $this->catalogItem;
    }

    /**
     * @param CatalogItem $catalogItem
     *
     * @return OrderDetail
     */
    public function setCatalogItem($catalogItem)
    {
        $this->catalogItem = $catalogItem;

        return $this;
    }

    /**
     * @return InventoryItem
     */
    public function getInventoryItem()
    {
        return $this->inventoryItem;
    }

    /**
     * @param InventoryItem $inventoryItem
     *
     * @return OrderDetail
     */
    public function setInventoryItem($inventoryItem)
    {
        $this->inventoryItem = $inventoryItem;

        return $this;
    }

    /**
     * Get orderedEan
     *
     * @return string
     */
    public function getOrderedEan()
    {
        return $this->orderedEan;
    }

    /**
     * Set orderedEan
     *
     * @param string $orderedEan
     * @return OrderDetail
     */
    public function setOrderedEan($orderedEan)
    {
        $this->orderedEan = $orderedEan;
        return $this;
    }


}

