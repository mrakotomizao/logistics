<?php

namespace LogisticsBundle\Tests\Entity;

use LogisticsBundle\Entity\CatalogItem;
use LogisticsBundle\Entity\CatalogPack;
use PHPUnit\Framework\TestCase;
use LogisticsBundle\Entity\CatalogPackDetail;


class CatalogPackDetailTest extends TestCase
{
    /**
     * @var CatalogPackDetail
     */
    private $catalogPackDetail;

    protected function setUp()
    {
        parent::setUp();

        $this->catalogPackDetail = new CatalogPackDetail();
    }

    public function testGetterAndSetter()
    {
        self::assertNull($this->catalogPackDetail->getId());

        $catalogItem = new CatalogItem();
        $this->catalogPackDetail->setCatalogItem($catalogItem);
        self::assertSame($catalogItem, $this->catalogPackDetail->getCatalogItem());

        $catalogPack = new CatalogPack();
        $this->catalogPackDetail->setCatalogPack($catalogPack);
        self::assertSame($catalogPack, $this->catalogPackDetail->getCatalogPack());

        $this->catalogPackDetail->setQuantity(64);
        self::assertEquals(64, $this->catalogPackDetail->getQuantity());

    }
}