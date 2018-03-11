<?php

namespace LogisticsBundle\Tests\Entity;

use AuthBundle\Entity\User;
use LogisticsBundle\Entity\Distributor;
use LogisticsBundle\Entity\OrderDetail;
use LogisticsBundle\Entity\PurchaseOrder;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use DateTime;


class PurchaseOrderTest extends WebTestCase
{
    /**
     * @var PurchaseOrder
     */
    private $purchaseOrder;

    protected function setUp()
    {
        parent::setUp();

        $this->purchaseOrder = new PurchaseOrder();
    }

    public function testGetterAndSetter()
    {
        self::assertNull($this->purchaseOrder->getId());

        $this->purchaseOrder->updateCreatedDate();
        self::assertInstanceOf(DateTime::class, $this->purchaseOrder->getCreatedDate());
        $date = new DateTime();
        $this->purchaseOrder->setCreatedDate($date);
        self::assertEquals($date, $this->purchaseOrder->getCreatedDate());

        $distributor = new Distributor();
        $this->purchaseOrder->setDistributor($distributor);
        $distributor->setName('Tototititata');
        self::assertSame($distributor, $this->purchaseOrder->getDistributor());

        $this->purchaseOrder->setSource('Prestashop, la boutique au top');
        self::assertEquals('Prestashop, la boutique au top', $this->purchaseOrder->getSource());

        $this->purchaseOrder->setSourceOrderNum('PO655957');
        self::assertEquals('PO655957', $this->purchaseOrder->getSourceOrderNum());

        $this->purchaseOrder->setContactName('Mère Michel');
        self::assertEquals('Mère Michel', $this->purchaseOrder->getContactName());

        $this->purchaseOrder->setContactNumber('+33556478124');
        self::assertEquals('33556478124', $this->purchaseOrder->getContactNumber());

        $this->purchaseOrder->setContactEmail('mama.michel@moulin.com');
        self::assertEquals('mama.michel@moulin.com', $this->purchaseOrder->getContactEmail());

        $this->purchaseOrder->setClientCode('MyCC');
        self::assertEquals('MyCC', $this->purchaseOrder->getClientCode());

        $this->purchaseOrder->setClientName('Le petit moulin');
        self::assertEquals('Le petit moulin', $this->purchaseOrder->getClientName());

        $this->purchaseOrder->setAddressLine1('3 rue du Moulin Vatrovite');
        self::assertEquals('3 rue du Moulin Vatrovite'.", ".$this->purchaseOrder->getZip()." ".$this->purchaseOrder->getCity(), $this->purchaseOrder->getDeliveryAddress());

        $date = new DateTime('2017-08-01');
        $this->purchaseOrder->setOrderDate($date);
        self::assertEquals(new DateTime('2017-08-01'), $this->purchaseOrder->getOrderDate());

        $date = new DateTime('2017-08-15');
        $this->purchaseOrder->setStartProcessingDate($date);
        self::assertEquals(new DateTime('2017-08-15'), $this->purchaseOrder->getStartProcessingDate());

        $date = new DateTime('2017-08-16');
        $this->purchaseOrder->setProcessedDate($date);
        self::assertEquals(new DateTime('2017-08-16'), $this->purchaseOrder->getProcessedDate());

        $user = new User();
        $this->purchaseOrder->setLastUpdateUser($user);
        self::assertSame($user, $this->purchaseOrder->getLastUpdateUser());

        $orderDetail_1 = new OrderDetail();
        $orderDetail_2 = new OrderDetail();
        $orderDetail_3 = new OrderDetail();
        $orderDetail_4 = new OrderDetail();
        $orderDetailsArray = array($orderDetail_3, $orderDetail_4);
        $this->purchaseOrder->addOrderDetails($orderDetail_1);
        self::assertContains($orderDetail_1, $this->purchaseOrder->getOrderDetails());
        self::assertNotContains($orderDetail_2, $this->purchaseOrder->getOrderDetails());
        self::assertNotContains($orderDetail_3, $this->purchaseOrder->getOrderDetails());
        self::assertNotContains($orderDetail_4, $this->purchaseOrder->getOrderDetails());
        self::assertSame($this->purchaseOrder, $this->purchaseOrder->getOrderDetails()[0]->getPurchaseOrder());

        $this->purchaseOrder->addOrderDetails($orderDetail_2);
        self::assertContains($orderDetail_2, $this->purchaseOrder->getOrderDetails());
        self::assertCount(2, $this->purchaseOrder->getOrderDetails());

//        $this->purchaseOrder->addOrderDetailsArray($orderDetailsArray);
//        self::assertContains($orderDetail_3, $this->purchaseOrder->getOrderDetails());
//        self::assertContains($orderDetail_4, $this->purchaseOrder->getOrderDetails());
//        self::assertCount(4, $this->purchaseOrder->getOrderDetails());

        $this->purchaseOrder->removeOrderDetails($orderDetail_3);
        self::assertNotContains($orderDetail_3, $this->purchaseOrder->getOrderDetails());
        self::assertContains($orderDetail_1, $this->purchaseOrder->getOrderDetails());
        self::assertContains($orderDetail_2, $this->purchaseOrder->getOrderDetails());
        //self::assertContains($orderDetail_4, $this->purchaseOrder->getOrderDetails());
    }

}