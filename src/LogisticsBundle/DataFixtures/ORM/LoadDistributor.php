<?php

namespace LogisticsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LogisticsBundle\Entity\Distributor;

class LoadDistributor extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager) {
        $defaultDistributor = new Distributor();
        $defaultDistributor->setCode('anovo');
        $defaultDistributor->setName('Anovo');

        $manager->persist($defaultDistributor);

        $manager->flush();
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 3;
    }
}