<?php

namespace AuthBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use AuthBundle\Entity\Client;

class ClientTest extends TestCase
{
    /**
     * @var Client
     */
    private $client;

    protected function setUp()
    {
        $this->client = new Client();
    }

    public function testGetterAndSetter() {
        $this->assertNull($this->client->getId());

        $this->client->setName('My random name');
        $this->assertEquals('My random name', $this->client->getName());
    }
}