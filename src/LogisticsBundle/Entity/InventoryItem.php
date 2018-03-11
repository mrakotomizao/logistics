<?php

namespace LogisticsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * InventoryItem
 *
 * @ORM\Table(name="inventory_item")
 * @ORM\Entity(repositoryClass="LogisticsBundle\Repository\InventoryItemRepository")
 */
class InventoryItem
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    private $id;

    /**
     * @var CatalogItem $catalogItem
     *
     * @ORM\ManyToOne(targetEntity="LogisticsBundle\Entity\CatalogItem")
     * @ORM\JoinColumn(nullable=false)
     */
    private $catalogItem;

    /**
     * @var string
     *
     * @ORM\Column(name="serial_number", type="string", length=255, unique=true)
     */
    private $serialNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="objenious_id", type="string", length=255, unique=true, nullable=true)
     */
    private $objeniousId;

    /**
     * @var bool
     *
     * @ORM\Column(name="valid", type="boolean", nullable=true)
     */
    private $valid;

    /**
     * @var string
     *
     * @ORM\Column(name="error", type="string", length=255, nullable=true)
     */
    private $error;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime", nullable=true, options={"default": 0})
     */
    private $createdDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="checked_date", type="datetime", nullable=true)
     */
    private $checkedDate;

    /**
     * @var string
     *
     * @ORM\Column(name="pack_serial", type="string", length=50, nullable=true)
     */
    private $packSerial;

//    /**
//     * @var PackRegistration
//     *
//     * @ORM\ManyToOne(targetEntity="LogisticsBundle\Entity\PackRegistration")
//     */
//    private $packRegistration;


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
     * Get catalogItem
     * 
     * @return CatalogItem
     */
    public function getCatalogItem()
    {
        return $this->catalogItem;
    }

    /**
     * Set catalogItem
     *
     * @param CatalogItem $catalogItem
     * @return InventoryItem
     */
    public function setCatalogItem($catalogItem)
    {
        $this->catalogItem = $catalogItem;
        return $this;
    }
    
    /**
     * Set serialNumber
     *
     * @param string $serialNumber
     *
     * @return InventoryItem
     */
    public function setSerialNumber($serialNumber)
    {
        $this->serialNumber = $serialNumber;

        return $this;
    }

    /**
     * Get serialNumber
     *
     * @return string
     */
    public function getSerialNumber()
    {
        return $this->serialNumber;
    }

    /**
     * Get Objenious ID
     *
     * @return string
     */
    public function getObjeniousId()
    {
        return $this->objeniousId;
    }

    /**
     * Set Objenious Id
     *
     * @param string $objeniousId
     *
     * @return InventoryItem
     */
    public function setObjeniousId($objeniousId)
    {
        $this->objeniousId = $objeniousId;
        return $this;
    }

    /**
     * Get isValid
     *
     * @return bool
     */
    public function isValid()
    {
        return $this->valid;
    }

    /**
     * Set isValid
     *
     * @param boolean $valid
     *
     * @return InventoryItem
     */
    public function setValid($valid)
    {
        $this->valid = $valid;

        return $this;
    }


    /**
     * Set error
     *
     * @param string $error
     *
     * @return InventoryItem
     */
    public function setError($error)
    {
        $this->error = $error;

        return $this;
    }

    /**
     * Get error
     *
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return InventoryItem
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

    /**
     * Get checkedDate
     *
     * @return \DateTime
     */
    public function getCheckedDate()
    {
        return $this->checkedDate;
    }

    /**
     * Set checkedDate
     *
     * @param \DateTime $checkedDate
     * @return InventoryItem
     */
    public function setCheckedDate($checkedDate)
    {
        $this->checkedDate = $checkedDate;
        return $this;
    }

    /**
     * Get packSerial
     *
     * @return string
     */
    public function getPackSerial()
    {
        return $this->packSerial;
    }

    /**
     * Set packSerial
     *
     * @param string $packSerial
     *
     * @return InventoryItem
     */
    public function setPackSerial($packSerial)
    {
        $this->packSerial = $packSerial;
        return $this;
    }

//    /**
//     * Get packRegistration
//     *
//     * @return PackRegistration
//     */
//    public function getPackRegistration()
//    {
//        return $this->packRegistration;
//    }
//
//    /**
//     * Set packRegistration
//     *
//     * @param PackRegistration $packRegistration
//     * @return InventoryItem
//     */
//    public function setPackRegistration($packRegistration)
//    {
//        $this->packRegistration = $packRegistration;
//
//        return $this;
//    }


}

