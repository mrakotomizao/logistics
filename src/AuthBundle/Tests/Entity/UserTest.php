<?php

namespace AuthBundle\Tests\Entity;

use LogisticsBundle\Entity\Distributor;
use PHPUnit\Framework\TestCase;
use AuthBundle\Entity\User;
use DateTime;

class UserTest extends TestCase {

    /**
     * @var User
     */
    private $user;

    protected function setUp()
    {
        parent::setUp();

        $this->user = new User();

        $distributor = new Distributor();
        $distributor->setName('MyDistributorName');
        $distributor->setCode('MyDistributorCode');
        $this->user->setDistributor($distributor);
    }

    public function testGetterAndSetter()
    {
        $this->assertNull($this->user->getId());
        $this->user->updateCreatedDate();
        self::assertInstanceOf(DateTime::class, $this->user->getCreatedDate());

        $this->user->setFirstname('Juliane');
        $this->assertEquals('Juliane', $this->user->getFirstname());

        $this->user->setLastname('Duval');
        $this->assertEquals('Duval', $this->user->getLastname());

        $this->assertEquals('MyDistributorName', $this->user->getDistributor()->getName());
        $this->assertEquals('MyDistributorCode', $this->user->getDistributor()->getCode());

        $date = new DateTime();
        $this->user->setCreatedDate($date);
        $this->assertEquals($date, $this->user->getCreatedDate());
    }
}