<?php

namespace LogisticsBundle\Tests\Entity;

use LogisticsBundle\Entity\CatalogPackDetail;
use PHPUnit\Framework\TestCase;
use LogisticsBundle\Entity\CatalogItem;
use DateTime;


class CatalogItemTest extends TestCase
{
    /**
     * @var CatalogItem
     */
    private $catalogItem;

    protected function setUp()
    {
        parent::setUp();

        $this->catalogItem = new CatalogItem();
    }

    public function testGetterAndSetter()
    {
        self::assertNull($this->catalogItem->getId());
        $this->catalogItem->updateCreatedDate();
        self::assertInstanceOf(DateTime::class, $this->catalogItem->getCreatedDate());

        $this->catalogItem->setEan('9780471117094');
        self::assertEquals('9780471117094', $this->catalogItem->getEan());

        $this->catalogItem->setDescription('MyItemDescription');
        self::assertEquals('MyItemDescription', $this->catalogItem->getDescription());

        $this->catalogItem->setRefName('ITEM-REF-NAME');
        self::assertEquals('ITEM-REF-NAME', $this->catalogItem->getRefName());

        $date = new DateTime();
        $this->catalogItem->setCreatedDate($date);
        self::assertEquals($date, $this->catalogItem->getCreatedDate());

        $catalogPackDetail_1 = new CatalogPackDetail();
        $catalogPackDetail_2 = new CatalogPackDetail();
//        $this->catalogItem->addCatalogPackDetail($catalogPackDetail_1);
//        self::assertContains($catalogPackDetail_1, $this->catalogItem->getCatalogPackDetails());
//        self::assertNotContains($catalogPackDetail_2, $this->catalogItem->getCatalogPackDetails());
//        self::assertSame($this->catalogItem, $this->catalogItem->getCatalogPackDetails()[0]->getCatalogItem());
//
//        $this->catalogItem->addCatalogPackDetail($catalogPackDetail_2);
//        self::assertContains($catalogPackDetail_2, $this->catalogItem->getCatalogPackDetails());
//        self::assertCount(2, $this->catalogItem->getCatalogPackDetails());
//
//        $this->catalogItem->removeCatalogPackDetail($catalogPackDetail_1);
//        self::assertNotContains($catalogPackDetail_1, $this->catalogItem->getCatalogPackDetails());
//        self::assertContains($catalogPackDetail_2, $this->catalogItem->getCatalogPackDetails());

    }

}