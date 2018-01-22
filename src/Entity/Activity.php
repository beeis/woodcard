<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActivityRepository")
 */
class Activity
{
    use TimestampableEntity;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var User|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @var OrderItem|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\OrderItem")
     */
    private $orderItem;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string")
     */
    private $changed;

    /**
     * @var string|null
     *
     * @ORM\Column(type="text")
     */
    private $changedComment;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }

    /**
     * @param User|null $user
     */
    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    /**
     * @return null|string
     */
    public function getChangedComment(): ?string
    {
        return $this->changedComment;
    }

    /**
     * @param null|string $changedComment
     */
    public function setChangedComment(?string $changedComment): void
    {
        $this->changedComment = $changedComment;
    }

    /**
     * @return OrderItem|null
     */
    public function getOrderItem(): ?OrderItem
    {
        return $this->orderItem;
    }

    /**
     * @param OrderItem|null $orderItem
     */
    public function setOrderItem(?OrderItem $orderItem): void
    {
        $this->orderItem = $orderItem;
    }

    /**
     * @return null|string
     */
    public function getChanged(): ?string
    {
        return $this->changed;
    }

    /**
     * @param null|string $changed
     */
    public function setChanged(?string $changed): void
    {
        $this->changed = $changed;
    }
}
