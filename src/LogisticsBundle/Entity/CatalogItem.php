<?php

namespace LogisticsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * CatalogItem
 *
 * @ORM\Table(name="catalog_item")
 * @ORM\Entity(repositoryClass="LogisticsBundle\Repository\CatalogItemRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class CatalogItem
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
     * @var string
     *
     * @ORM\Column(name="ref_name", type="string", length=255)
     */
    private $refName;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="ean", type="string", length=255, unique=true)
     */
    private $ean;

    /**
     * @var string
     *
     * @ORM\Column(name="brand", type="string", length=255)
     */
    private $brand;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime")
     */
    private $createdDate;

    /**
     * CatalogItem constructor.
     */
    public function __construct()
    {
        $this->catalogPackDetails = new ArrayCollection();
    }

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
     * Set refName
     *
     * @param string $refName
     *
     * @return CatalogItem
     */
    public function setRefName($refName)
    {
        $this->refName = $refName;

        return $this;
    }

    /**
     * Get refName
     *
     * @return string
     */
    public function getRefName()
    {
        return $this->refName;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return CatalogItem
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Get ean
     *
     * @return string
     */
    public function getEan()
    {
        return $this->ean;
    }

    /**
     * Set ean
     *
     * @param string $ean
     *
     * @return CatalogItem
     */
    public function setEan($ean)
    {
        $this->ean = $ean;

        return $this;
    }

    /**
     * Get brand
     *
     * @return string
     */
    public function getBrand()
    {
        return $this->brand;
    }

    /**
     * Set brand
     *
     * @param string $brand
     * @return CatalogItem
     */
    public function setBrand($brand)
    {
        $this->brand = $brand;
        return $this;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return CatalogItem
     */
    public function setCreatedDate($createdDate)
    {
        $this->createdDate = $createdDate;

        return $this;
    }

    /**
     * Get createdDate
     *
     * @return \DateTime
     */
    public function getCreatedDate()
    {
        return $this->createdDate;
    }

    /**
     * Fills createdDate on INSERT
     *
     * @ORM\PrePersist()
     */
    public function updateCreatedDate()
    {
        $this->createdDate = new \DateTime();
    }

}

