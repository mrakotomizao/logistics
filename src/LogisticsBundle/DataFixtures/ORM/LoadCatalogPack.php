<?php

namespace LogisticsBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use LogisticsBundle\Entity\CatalogPack;
use LogisticsBundle\Entity\CatalogPackDetail;

class LoadCatalogPack extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * @param ObjectManager $manager (EntityManager)
     */
    public function load(ObjectManager $manager)
    {
        // CatalogPacks to be added
        $packs = array(
            'PACK-STARTERPACK-LORA-01' => array('ITEM-TYPE-A' => 1,
                                                'ITEM-TYPE-B' => 2,
                                                'ITEM-TYPE-C' => 3),
            'PACK-SOLO-TEMPERATURE-LORA-01' => array('ITEM-TYPE-A' => 2),
            'PACK-SOLO-GAZ-LORA-01' => array('ITEM-TYPE-D' => 4)
        );

        $catalogItemRepository = $manager->getRepository('LogisticsBundle:CatalogItem');

        foreach ($packs as $packRefName => $contents)
        {
            $catalogPack = new CatalogPack();
            $catalogPack->setRefName($packRefName);
            $catalogPack->setDescription('Description ' . $packRefName);
            $catalogPack->setEan('761234567891');
            foreach ($contents as $itemRefName => $quantity)
            {
                $catalogItem = $catalogItemRepository->findOneBy(array('refName' => $itemRefName));

                $catalogPackDetail = new CatalogPackDetail();
                $catalogPackDetail->setCatalogItem($catalogItem);
                $catalogPackDetail->setQuantity($quantity);

                $catalogPack->addCatalogPackDetail($catalogPackDetail);
            }
            $manager->persist($catalogPack);
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
        return 2;
    }
}