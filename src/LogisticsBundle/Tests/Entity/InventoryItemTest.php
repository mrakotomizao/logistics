<?php

namespace LogisticsBundle\Tests\Entity;

use LogisticsBundle\Entity\CatalogItem;
use PHPUnit\Framework\TestCase;
use LogisticsBundle\Entity\InventoryItem;


class InventoryItemTest extends TestCase
{
    /**
     * @var InventoryItem
     */
    private $inventoryItem;

    protected function setUp()
    {
        parent::setUp();

        $this->inventoryItem = new InventoryItem();
    }

    public function testGetterAndSetter()
    {
        self::assertNull($this->inventoryItem->getId());

        $this->inventoryItem->setSerialNumber('P2458061C');
        self::assertEquals('P2458061C', $this->inventoryItem->getSerialNumber());

        $this->inventoryItem->setValid(true);
        self::assertTrue($this->inventoryItem->isValid());
        $this->inventoryItem->setValid(false);
        self::assertFalse($this->inventoryItem->isValid());

        $this->inventoryItem->setError('Oopsie doopsie');
        self::assertEquals('Oopsie doopsie', $this->inventoryItem->getError());

        $catalogItem = new CatalogItem();
        $this->inventoryItem->setCatalogItem($catalogItem);
        self::assertSame($catalogItem, $this->inventoryItem->getCatalogItem());
    }
}