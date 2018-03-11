<?php

namespace LogisticsBundle\Tests\Entity;

use LogisticsBundle\Entity\CatalogItem;
use LogisticsBundle\Entity\InventoryItem;
use LogisticsBundle\Entity\PurchaseOrder;
use PHPUnit\Framework\TestCase;
use LogisticsBundle\Entity\OrderDetail;


class OrderDetailTest extends TestCase
{
    /**
     * @var OrderDetail
     */
    private $orderDetail;

    protected function setUp()
    {
        parent::setUp();

        $this->orderDetail = new OrderDetail();
    }

    public function testGetterAndSetter()
    {
        self::assertNull($this->orderDetail->getId());

        $this->orderDetail->setGroupNumber(42);
        self::assertEquals(42, $this->orderDetail->getGroupNumber());

        $this->orderDetail->setGroupDescription("Miscellaneous");
        self::assertEquals("Miscellaneous", $this->orderDetail->getGroupDescription());

        $inventoryItem = new InventoryItem();
        $this->orderDetail->setInventoryItem($inventoryItem);
        self::assertSame($inventoryItem, $this->orderDetail->getInventoryItem());

        $catalogItem = new CatalogItem();
        $this->orderDetail->setCatalogItem($catalogItem);
        self::assertSame($catalogItem, $this->orderDetail->getCatalogItem());

        $purchaseOrder = new PurchaseOrder();
        $this->orderDetail->setPurchaseOrder($purchaseOrder);
        self::assertSame($purchaseOrder, $this->orderDetail->getPurchaseOrder());
    }
}