<?php

namespace LogisticsBundle\Tests\Entity;

use LogisticsBundle\Entity\CatalogPackDetail;
use PHPUnit\Framework\TestCase;
use LogisticsBundle\Entity\CatalogPack;
use DateTime;


class CatalogPackTest extends TestCase
{
    /**
     * @var CatalogPack
     */
    private $catalogPack;

    protected function setUp()
    {
        parent::setUp();

        $this->catalogPack = new CatalogPack();
    }

    public function testGetterAndSetter()
    {
        self::assertNull($this->catalogPack->getId());
        $this->catalogPack->updateCreatedDate();
        self::assertInstanceOf(DateTime::class, $this->catalogPack->getCreatedDate());

        $this->catalogPack->setEan('9780471117095');
        self::assertEquals('9780471117095', $this->catalogPack->getEan());

        $this->catalogPack->setDescription('MyPackDescription');
        self::assertEquals('MyPackDescription', $this->catalogPack->getDescription());

        $this->catalogPack->setRefName('PACK-REF-NAME');
        self::assertEquals('PACK-REF-NAME', $this->catalogPack->getRefName());

        $date = new DateTime();
        $this->catalogPack->setCreatedDate($date);
        self::assertEquals($date, $this->catalogPack->getCreatedDate());

        $catalogPackDetail_1 = new CatalogPackDetail();
        $catalogPackDetail_2 = new CatalogPackDetail();
        $this->catalogPack->addCatalogPackDetail($catalogPackDetail_1);
        self::assertContains($catalogPackDetail_1, $this->catalogPack->getCatalogPackDetails());
        self::assertNotContains($catalogPackDetail_2, $this->catalogPack->getCatalogPackDetails());
        self::assertSame($this->catalogPack, $this->catalogPack->getCatalogPackDetails()[0]->getCatalogPack());

        $this->catalogPack->addCatalogPackDetail($catalogPackDetail_2);
        self::assertContains($catalogPackDetail_2, $this->catalogPack->getCatalogPackDetails());
        self::assertCount(2, $this->catalogPack->getCatalogPackDetails());

        $this->catalogPack->removeCatalogPackDetail($catalogPackDetail_1);
        self::assertNotContains($catalogPackDetail_1, $this->catalogPack->getCatalogPackDetails());
        self::assertContains($catalogPackDetail_2, $this->catalogPack->getCatalogPackDetails());
    }
}