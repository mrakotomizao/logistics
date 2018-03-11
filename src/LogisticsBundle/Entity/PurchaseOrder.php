<?php

namespace LogisticsBundle\Entity;

use AuthBundle\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * PurchaseOrder
 *
 * @ORM\Table(name="purchase_order", uniqueConstraints={@ORM\UniqueConstraint(name="unique_source_order", columns={"source", "source_order_num"})})
 * @ORM\Entity(repositoryClass="LogisticsBundle\Repository\PurchaseOrderRepository")
 * @ORM\HasLifecycleCallbacks()
 * @Serializer\VirtualProperty("status", exp="object.getStatus()")
 * @Serializer\VirtualProperty("deliveryAddress", exp="object.getDeliveryAddress()")
 * @Serializer\VirtualProperty("isOpen", exp="object.isOpen()")
 *
 */
class PurchaseOrder
{
    const STATUS_UNPROCESSED = 'A traiter';
    const STATUS_ONGOING = 'En cours';
    const STATUS_PROCESSED = 'Prête';
    const STATUS_DELIVERED = 'Livrée';

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
     * @var Distributor $distributor
     *
     * @ORM\ManyToOne(targetEntity="LogisticsBundle\Entity\Distributor")
     * @ORM\JoinColumn(nullable=false)
     */
    private $distributor;

    /**
     * @var string
     *
     * @ORM\Column(name="address_line_1", type="string", length=255)
     */
    private $addressLine1;

    /**
     * @var string
     *
     * @ORM\Column(name="address_line_2", type="string", length=255, nullable=true)
     */
    private $addressLine2;

    /**
     * @var string
     *
     * @ORM\Column(name="zip", type="string", length=15)
     */
    private $zip;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=50)
     */
    private $city;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_date", type="datetime", options={"default": 0})
     */
    private $createdDate;

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=255)
     */
    private $source;

    /**
     * @var string
     *
     * @ORM\Column(name="source_order_num", type="string", length=255)
     */
    private $sourceOrderNum;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_processing_date", type="datetime", nullable=true)
     */
    private $startProcessingDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="processed_date", type="datetime", nullable=true)
     */
    private $processedDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="delivery_date", type="datetime", nullable=true)
     */
    private $deliveryDate;

    /**
     * @var string
     *
     * @ORM\Column(name="client_code", type="string", length=255)
     */
    private $clientCode;

    /**
     * @var string
     *
     * @ORM\Column(name="client_name", type="string", length=255)
     */
    private $clientName;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_name", type="string", length=255, nullable=true)
     */
    private $contactName;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_number", type="string", length=255, nullable=true)
     */
    private $contactNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="contact_email", type="string", length=255, nullable=true)
     */
    private $contactEmail;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="order_date", type="datetime")
     */
    private $orderDate;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AuthBundle\Entity\User")
     */
    private $lastUpdateUser;

    /**
     * @var ArrayCollection (OrderDetail)
     *
     * @ORM\OneToMany(targetEntity="LogisticsBundle\Entity\OrderDetail", mappedBy="purchaseOrder", cascade={"all"})
     */
    private $orderDetails;

    /**
     * PurchaseOrder constructor.
     */
    public function __construct()
    {
        $this->orderDetails = new ArrayCollection();
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
     * @return Distributor
     */
    public function getDistributor()
    {
        return $this->distributor;
    }

    /**
     * @param Distributor $distributor
     *
     * @return PurchaseOrder
     */
    public function setDistributor($distributor)
    {
        $this->distributor = $distributor;

        return $this;
    }

    /**
     * Get deliveryAddress
     *
     * @Serializer\VirtualProperty()
     *
     * @return string
     */
    public function getDeliveryAddress()
    {
        $deliveryAddress = $this->getAddressLine1();
        if($this->getAddressLine2())
        {
            $deliveryAddress = $deliveryAddress.", ".$this->getAddressLine2();
        }
        $deliveryAddress = $deliveryAddress.", ".$this->getZip()." ".$this->getCity();
        return $deliveryAddress;
    }

    /**
     * Get addressLine1
     *
     * @return string
     */
    public function getAddressLine1()
    {
        return $this->addressLine1;
    }

    /**
     * Set addressLine1
     *
     * @param string $addressLine1
     * @return PurchaseOrder
     */
    public function setAddressLine1($addressLine1)
    {
        $this->addressLine1 = $addressLine1;
        return $this;
    }

    /**
     * Get addressLine2
     *
     * @return string
     */
    public function getAddressLine2()
    {
        return $this->addressLine2;
    }

    /**
     * Set addressLine2
     *
     * @param string $addressLine2
     * @return PurchaseOrder
     */
    public function setAddressLine2($addressLine2)
    {
        $this->addressLine2 = $addressLine2;
        return $this;
    }

    /**
     * Get zip
     *
     * @return string
     */
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set zip
     *
     * @param string $zip
     * @return PurchaseOrder
     */
    public function setZip($zip)
    {
        $this->zip = $zip;
        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return PurchaseOrder
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return PurchaseOrder
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
     * Set source
     *
     * @param string $source
     *
     * @return PurchaseOrder
     */
    public function setSource($source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set sourceOrderNum
     *
     * @param string $sourceOrderNum
     *
     * @return PurchaseOrder
     */
    public function setSourceOrderNum($sourceOrderNum)
    {
        $this->sourceOrderNum = $sourceOrderNum;

        return $this;
    }

    /**
     * Get sourceOrderNum
     *
     * @return string
     */
    public function getSourceOrderNum()
    {
        return $this->sourceOrderNum;
    }

    /**
     * Set startProcessingDate
     *
     * @param \DateTime $startProcessingDate
     *
     * @return PurchaseOrder
     */
    public function setStartProcessingDate($startProcessingDate)
    {
        $this->startProcessingDate = $startProcessingDate;

        return $this;
    }

    /**
     * Get startProcessingDate
     *
     * @return \DateTime
     */
    public function getStartProcessingDate()
    {
        return $this->startProcessingDate;
    }

    /**
     * Set processedDate
     *
     * @param \DateTime $processedDate
     *
     * @return PurchaseOrder
     */
    public function setProcessedDate($processedDate)
    {
        $this->processedDate = $processedDate;

        return $this;
    }

    /**
     * Get deliveryDate
     *
     * @return \DateTime
     */
    public function getDeliveryDate()
    {
        return $this->deliveryDate;
    }

    /**
     * Set deliveryDate
     *
     * @param \DateTime $deliveryDate
     *
     * @return PurchaseOrder
     */
    public function setDeliveryDate($deliveryDate)
    {
        $this->deliveryDate = $deliveryDate;

        return $this;
    }

    /**
     * Get processedDate
     *
     * @return \DateTime
     */
    public function getProcessedDate()
    {
        return $this->processedDate;
    }

    /**
     * Set clientCode
     *
     * @param string $clientCode
     *
     * @return PurchaseOrder
     */
    public function setClientCode($clientCode)
    {
        $this->clientCode = $clientCode;

        return $this;
    }

    /**
     * Get clientCode
     *
     * @return string
     */
    public function getClientCode()
    {
        return $this->clientCode;
    }

    /**
     * Set clientName
     *
     * @param string $clientName
     *
     * @return PurchaseOrder
     */
    public function setClientName($clientName)
    {
        $this->clientName = $clientName;

        return $this;
    }

    /**
     * Get clientName
     *
     * @return string
     */
    public function getClientName()
    {
        return $this->clientName;
    }

    /**
     * Set contactName
     *
     * @param string $contactName
     *
     * @return PurchaseOrder
     */
    public function setContactName($contactName)
    {
        $this->contactName = $contactName;

        return $this;
    }

    /**
     * Get contactName
     *
     * @return string
     */
    public function getContactName()
    {
        return $this->contactName;
    }

    /**
     * Set contactNumber
     *
     * @param string $contactNumber
     *
     * @return PurchaseOrder
     */
    public function setContactNumber($contactNumber)
    {
        $this->contactNumber = $contactNumber;

        return $this;
    }

    /**
     * Get contactNumber
     *
     * @return string
     */
    public function getContactNumber()
    {
        return $this->contactNumber;
    }

    /**
     * Set contactEmail
     *
     * @param string $contactEmail
     *
     * @return PurchaseOrder
     */
    public function setContactEmail($contactEmail)
    {
        $this->contactEmail = $contactEmail;

        return $this;
    }

    /**
     * Get contactEmail
     *
     * @return string
     */
    public function getContactEmail()
    {
        return $this->contactEmail;
    }

    /**
     * Set orderDate
     *
     * @param \DateTime $orderDate
     *
     * @return PurchaseOrder
     */
    public function setOrderDate($orderDate)
    {
        $this->orderDate = $orderDate;

        return $this;
    }

    /**
     * Get orderDate
     *
     * @return \DateTime
     */
    public function getOrderDate()
    {
        return $this->orderDate;
    }

    /**
     * Add a detail line to the PurchaseOrder
     *
     * @param OrderDetail $orderDetail
     * @return PurchaseOrder
     */
    public function addOrderDetails(OrderDetail $orderDetail)
    {
        $this->orderDetails[] = $orderDetail;

        $orderDetail->setPurchaseOrder($this);

        return $this;
    }

    /**
     * Add an array of detail lines to the PurchaseOrder
     *
     * @param ArrayCollection $orderDetails
     * @return PurchaseOrder
     */
    public function addOrderDetailsArray(ArrayCollection $orderDetails)
    {
        foreach ($orderDetails as $orderDetail)
        {
            $this->addOrderDetails($orderDetail);
        }

        return $this;
    }

    /**
     * Remove a detail line from the PurchaseOrder
     *
     * @param OrderDetail $orderDetail
     *
     * @return PurchaseOrder
     */
    public function removeOrderDetails(OrderDetail $orderDetail)
    {
        $this->orderDetails->removeElement($orderDetail);

        return $this;
    }

    /**
     * Get orderDetails
     *
     * @return ArrayCollection<OrderDetail>
     */
    public function getOrderDetails()
    {
        return $this->orderDetails;
    }

    /**
     * Get LastUpdateUser
     *
     * @return User
     */
    public function getLastUpdateUser()
    {
        return $this->lastUpdateUser;
    }

    /**
     * Set LastUpdateUser
     *
     * @param User $lastUpdateUser
     * @return PurchaseOrder
     */
    public function setLastUpdateUser($lastUpdateUser)
    {
        $this->lastUpdateUser = $lastUpdateUser;
        return $this;
    }

    /**
     * Get Status
     *
     * @Serializer\VirtualProperty()
     *
     * @return string;
     */
    public function getStatus()
    {
        /*
         * If the start processing date is not set then the order is considered not to be processed.
         */
        if (empty($this->startProcessingDate))
        {
            return self::STATUS_UNPROCESSED;
        }
        /*
         * If the start processing date exists but the processed date is not set, then the order
         * is considered to be under processing.
         */
        if (empty($this->processedDate))
        {
            return self::STATUS_ONGOING;
        }
        /**
         * If the processed date exists but the delivery date is not set, then the order
         * is considered to processed but not yet delivered.
         */
        if (empty($this->deliveryDate))
        {
            return self::STATUS_PROCESSED;
        }
        /**
         * Otherwise, it means that all dates are filled and the order can be considered as delivered.
         */
        return self::STATUS_DELIVERED;
    }

    /**
     * Is Open
     *
     * @Serializer\VirtualProperty()
     *
     * @return boolean;
     */
    public function isOpen() {
        return empty($this->processedDate);
    }

}

