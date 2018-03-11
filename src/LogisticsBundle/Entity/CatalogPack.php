<?php

namespace LogisticsBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * CatalogPack
 *

 * @ORM\Table(name="catalog_pack")
 * @ORM\Entity(repositoryClass="LogisticsBundle\Repository\CatalogPackRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class CatalogPack
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
     * @var ArrayCollection (CatalogPackDetail)
     *
     * @ORM\OneToMany(targetEntity="LogisticsBundle\Entity\CatalogPackDetail", mappedBy="catalogPack", cascade={"all"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $catalogPackDetails;

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
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime")
     */
    private $createdDate;

    /**
     * CatalogPack constructor.
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
     * @return ArrayCollection (CatalogPackDetail)
     */
    public function getCatalogPackDetails()
    {
        return $this->catalogPackDetails;
    }

    /**
     * @param CatalogPackDetail $catalogPackDetail
     *
     * @return CatalogPack
     */
    public function addCatalogPackDetail(CatalogPackDetail $catalogPackDetail)
    {
        $this->catalogPackDetails[] = $catalogPackDetail;

        $catalogPackDetail->setCatalogPack($this);

        return $this;
    }

    /**
     * @param CatalogPackDetail $catalogPackDetail
     *
     * @return CatalogPack
     */
    public function removeCatalogPackDetail(CatalogPackDetail $catalogPackDetail)
    {
        $this->catalogPackDetails->removeElement($catalogPackDetail);

        return $this;
    }

    /**
     * Set refName
     *
     * @param string $refName
     *
     * @return CatalogPack
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
     * @return CatalogPack
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
     * @return CatalogPack
     */
    public function setEan($ean)
    {
        $this->ean = $ean;

        return $this;
    }



    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return CatalogPack
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

