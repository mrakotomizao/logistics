<?php

namespace LogisticsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LogisticsBundle\Entity\CatalogItem;

class LoadCatalogItem extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager (EntityManager)
     */
    public function load(ObjectManager $manager)
    {
        // refNames of CatalogItems to be added
        $refNames = array(
            'ITEM-TYPE-A',
            'ITEM-TYPE-B',
            'ITEM-TYPE-C',
            'ITEM-TYPE-D'
        );

        foreach ($refNames as $refName) {
            $catalogItem = new CatalogItem();
            $catalogItem->setRefName($refName);
            $catalogItem->setDescription('Description ' . $refName);
            $catalogItem->setEan('761234567890');
            $manager->persist($catalogItem);
        }

        $manager->flush();
    }


    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 1;
    }
}