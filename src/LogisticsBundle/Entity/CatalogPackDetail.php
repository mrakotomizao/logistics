<?php

namespace LogisticsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CatalogPackDetail
 *
 * @ORM\Table(name="catalog_pack_detail")
 * @ORM\Entity(repositoryClass="LogisticsBundle\Repository\CatalogPackDetailRepository")
 */
class CatalogPackDetail
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var CatalogItem
     *
     * @ORM\ManyToOne(targetEntity="LogisticsBundle\Entity\CatalogItem", cascade={"refresh"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $catalogItem;

    /**
     * @var CatalogPack
     *
     * @ORM\ManyToOne(targetEntity="LogisticsBundle\Entity\CatalogPack", inversedBy="catalogPackDetails")
     * @ORM\JoinColumn(nullable=false)
     */
    private $catalogPack;

    /**
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer")
     */
    private $quantity;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set quantity
     *
     * @param integer $quantity
     *
     * @return CatalogPackDetail
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;

        return $this;
    }

    /**
     * Get quantity
     *
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @return CatalogItem
     */
    public function getCatalogItem()
    {
        return $this->catalogItem;
    }

    /**
     * @param CatalogItem $catalogItem
     *
     * @return CatalogPackDetail
     */
    public function setCatalogItem($catalogItem)
    {
        $this->catalogItem = $catalogItem;
        return $this;
    }

    /**
     * @return CatalogPack
     */
    public function getCatalogPack()
    {
        return $this->catalogPack;
    }

    /**
     * @param CatalogPack $catalogPack
     *
     * @return CatalogPackDetail
     */
    public function setCatalogPack($catalogPack)
    {
        $this->catalogPack = $catalogPack;

        return $this;
    }

}

