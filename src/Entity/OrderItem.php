<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderItemRepository")
 */
class OrderItem
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var UploadedFile|null
     */
    private $file;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $photo;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $model;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $print;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $psd;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text", nullable=true)
     */
    private $inscription;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default":true})
     */
    private $active = true;

    /**
     * @var string|null
     *
     * @ORM\Column(type="bigint")
     */
    private $orderId;

    /**
     * @var Order|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Order", inversedBy="items")
     * @ORM\JoinColumn(name="order_fid", nullable=true)
     */
    private $order;

    /**
     * duplicate order item
     */
    public function __clone()
    {
        $this->id = null;
        $this->createdAt = null;
        $this->updatedAt = null;
        $this->model = null;
        $this->psd = null;
        $this->print = null;
        $this->active = true;
        $this->comment = null;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return UploadedFile|null
     */
    public function getFile(): ?UploadedFile
    {
        return $this->file;
    }

    /**
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file): void
    {
        $this->file = $file;
    }

    /**
     * @return null|string
     */
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    /**
     * @param null|string $photo
     */
    public function setPhoto(?string $photo): void
    {
        $this->photo = $photo;
    }

    /**
     * @return null|string
     */
    public function getModel(): ?string
    {
        return $this->model;
    }

    /**
     * @param null|string $model
     */
    public function setModel(?string $model): void
    {
        $this->model = $model;
    }

    /**
     * @return null|string
     */
    public function getPsd(): ?string
    {
        return $this->psd;
    }

    /**
     * @param null|string $psd
     */
    public function setPsd(?string $psd): void
    {
        $this->psd = $psd;
    }

    /**
     * @return null|string
     */
    public function getComment(): ?string
    {
        return $this->comment;
    }

    /**
     * @param null|string $comment
     */
    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active;
    }

    /**
     * @param bool $active
     */
    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    /**
     * @return null|string
     */
    public function getOrderId(): ?string
    {
        return $this->orderId;
    }

    /**
     * @param null|string $orderId
     */
    public function setOrderId(?string $orderId): void
    {
        $this->orderId = $orderId;
    }

    /**
     * @return null|string
     */
    public function getPrint(): ?string
    {
        return $this->print;
    }

    /**
     * @param null|string $print
     */
    public function setPrint(?string $print): void
    {
        $this->print = $print;
    }

    /**
     * @return null|string
     */
    public function getInscription(): ?string
    {
        return $this->inscription;
    }

    /**
     * @param null|string $inscription
     */
    public function setInscription(?string $inscription): void
    {
        $this->inscription = $inscription;
    }

    /**
     * @return Order|null
     */
    public function getOrder(): ?Order
    {
        return $this->order;
    }

    /**
     * @param Order|null $order
     */
    public function setOrder(?Order $order): void
    {
        $this->order = $order;
    }
}
