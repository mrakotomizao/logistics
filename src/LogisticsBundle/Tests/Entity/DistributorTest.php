<?php

namespace LogisticsBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use LogisticsBundle\Entity\Distributor;
use DateTime;


class DistributorTest extends TestCase
{
    /**
     * @var Distributor
     */
    private $distributor;

    protected function setUp()
    {
        parent::setUp();

        $this->distributor = new Distributor();
    }

    public function testGetterAndSetter()
    {
        self::assertNull($this->distributor->getId());

        $this->distributor->updateCreatedDate();
        self::assertInstanceOf(DateTime::class, $this->distributor->getCreatedDate());

        $this->distributor->setCode('MyDistributorCode');
        self::assertEquals('MyDistributorCode', $this->distributor->getCode());

        $this->distributor->setName('MyDistributorName');
        self::assertEquals('MyDistributorName', $this->distributor->getName());

        $date = new DateTime();
        $this->distributor->setCreatedDate($date);
        self::assertEquals($date, $this->distributor->getCreatedDate());
    }
}