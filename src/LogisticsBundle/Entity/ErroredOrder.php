<?php

namespace LogisticsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ErroredOrder
 *
 * @ORM\Table(name="errored_order", uniqueConstraints={@ORM\UniqueConstraint(name="unique_source_order", columns={"source", "source_order_num"})})
 * @ORM\Entity(repositoryClass="LogisticsBundle\Repository\ErroredOrderRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ErroredOrder
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
     * @ORM\Column(name="order_message", type="text")
     */
    private $orderMessage;

    /**
     * @var string
     *
     * @ORM\Column(name="error_messages", type="text")
     */
    private $errorMessages;

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
     * @ORM\Column(name="created_date", type="datetime")
     */
    private $createdDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_processed_date", type="datetime")
     */
    private $lastProcessedDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="closed_date", type="datetime", nullable=true)
     */
    private $closedDate;

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
     * Set orderMessage
     *
     * @param string $orderMessage
     *
     * @return ErroredOrder
     */
    public function setOrderMessage($orderMessage)
    {
        $this->orderMessage = $orderMessage;

        return $this;
    }

    /**
     * Get orderMessage
     *
     * @return string
     */
    public function getOrderMessage()
    {
        return $this->orderMessage;
    }

    /**
     * Get errorMessages
     *
     * @return string
     */
    public function getErrorMessages()
    {
        return $this->errorMessages;
    }

    /**
     * Set errorMessages
     *
     * @param string $errorMessages
     * @return ErroredOrder
     */
    public function setErrorMessages($errorMessages)
    {
        $this->errorMessages = $errorMessages;
        return $this;
    }

    /**
     * Appends an error message with a new line
     *
     * @param string $errorMessage
     * @return ErroredOrder
     */
    public function addErrorMessage($errorMessage)
    {
        $this->errorMessages .= $errorMessage . "\r";
        return $this;
    }

    /**
     * Set source
     *
     * @param string $source
     *
     * @return ErroredOrder
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
     * @return ErroredOrder
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
     * Set createdDate
     *
     * @param \DateTime $createdDate
     *
     * @return ErroredOrder
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
     * Set lastProcessedDate
     *
     * @param \DateTime $lastProcessedDate
     *
     * @return ErroredOrder
     */
    public function setLastProcessedDate($lastProcessedDate)
    {
        $this->lastProcessedDate = $lastProcessedDate;

        return $this;
    }

    /**
     * Get lastProcessedDate
     *
     * @return \DateTime
     */
    public function getLastProcessedDate()
    {
        return $this->lastProcessedDate;
    }

    /**
     * Fills createdDate on INSERT and UPDATE
     *
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function updateLastProcessedDate()
    {
        $this->lastProcessedDate = new \DateTime();
    }

    /**
     * Set closedDate
     *
     * @param \DateTime $closedDate
     *
     * @return ErroredOrder
     */
    public function setClosedDate($closedDate)
    {
        $this->closedDate = $closedDate;

        return $this;
    }

    /**
     * Get closedDate
     *
     * @return \DateTime
     */
    public function getClosedDate()
    {
        return $this->closedDate;
    }
}

