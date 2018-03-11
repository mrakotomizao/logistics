<?php

namespace LogisticsBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class OrdersControllerTest extends WebTestCase
{
    public function testPostOrdersActionNewOrders()
    {
        // Valid token need to be provided for test case to succeed.
        $token = 'ZGJjOTEyMTliN2JjNzA4MjY1OTMyMjhlNmRiOWFjNmRiODk5ZTRhMDU3NzczYjAyYjM2ZWVhMDZlZDM5OWUyNA';
        dump('Bearer ' . $token);
        $this->client = static::createClient();
        $em = $this->client
            ->getContainer()
            ->get('doctrine')
            ->getManager();
        $orderRepository = $em->getRepository('LogisticsBundle:PurchaseOrder');
        $order_1 = $orderRepository->findOneBySourceOrderNum("1");
        $order_2 = $orderRepository->findOneBySourceOrderNum("2");
//        $em->remove($order_1);
//        $em->remove($order_2);
        $em->flush();
        $this->client->request(
            'POST',
            '/api/orders',
            array(), //parameters
            array(), //files
            array('HTTP_AUTHORIZATION' => 'Bearer ' . $token,
                  'CONTENT_TYPE' => 'application/json'), //server
            file_get_contents('./src/LogisticsBundle/Tests/Resources/OrdersSample.json')
        );
        //$this->assertJsonResponse($this->client->getResponse(), Response::HTTP_CREATED, false);
    }

    public function testPostOrdersActionExistingOrders()
    {
        // Valid token need to be provided for test case to succeed.
        $token = 'ZGJjOTEyMTliN2JjNzA4MjY1OTMyMjhlNmRiOWFjNmRiODk5ZTRhMDU3NzczYjAyYjM2ZWVhMDZlZDM5OWUyNA';
        $this->client = static::createClient();
        $this->client->request(
            'POST',
            '/api/orders',
            array(), //parameters
            array(), //files
            array('HTTP_AUTHORIZATION' => 'Bearer ' . $token,
                'CONTENT_TYPE' => 'application/json'), //server
            file_get_contents('./src/LogisticsBundle/Tests/Resources/OrdersSample.json')
        );
        //$this->assertJsonResponse($this->client->getResponse(), Response::HTTP_INTERNAL_SERVER_ERROR, false);

//        $this->assertContains("An exception occurred while executing 'INSERT INTO purchase_order", $this->client->getResponse()->getContent());
    }

    protected function assertJsonResponse($response, $statusCode = 200)
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', 'application/json'),
            $response->headers
        );
    }
}
