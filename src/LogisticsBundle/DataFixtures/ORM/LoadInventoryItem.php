<?php

namespace LogisticsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LogisticsBundle\Entity\InventoryItem;

class LoadInventoryItem extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager (EntityManager)
     */
    public function load(ObjectManager $manager)
    {
        $catalogItemRepository = $manager->getRepository('LogisticsBundle:CatalogItem');

        $inventoryItem = new InventoryItem();
        $inventoryItem->setSerialNumber("70B3D59BA0000179");
        $catalogItem = $catalogItemRepository->findOneByRefName('ITEM-TYPE-B');
        $inventoryItem->setCatalogItem($catalogItem);
        $manager->persist($inventoryItem);

        $inventoryItem = new InventoryItem();
        $inventoryItem->setSerialNumber("70B3D580A0100F43");
        $catalogItem = $catalogItemRepository->findOneByRefName('ITEM-TYPE-C');
        $inventoryItem->setCatalogItem($catalogItem);
        $manager->persist($inventoryItem);

        $inventoryItem = new InventoryItem();
        $inventoryItem->setSerialNumber("70B3D59BA0000455");
        $catalogItem = $catalogItemRepository->findOneByRefName('ITEM-TYPE-D');
        $inventoryItem->setCatalogItem($catalogItem);
        $manager->persist($inventoryItem);

        $manager->flush();
    }


    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 4;
    }
}