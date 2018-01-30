<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Activity;
use App\Entity\OrderItem;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class ActivityManager
 *
 * @package App\Manager
 */
class ActivityManager implements ActivityManagerInterface
{
    /**
     * @var array
     */
    private $changed = [
        'new' => 'Загружено <a href="{image_base}/%s">новое Фото</a> и комментарий - %s',
        'psd' => 'Загружено <a href="{image_base}/%s">новое PSD</a>',
        'model' => 'Загружен <a href="{image_base}/%s">новый Макет</a>',
        'inscription' => 'Добавлено надпись: %s',
        'comment' => 'Добавлен комментарий: %s',
        'active' => 'Статус изменен на %s',
    ];

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * ActivityManager constructor.
     *
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function handleChanges(OrderItem $orderItem, array $changedSet = []): ?Activity
    {
        $intersect = array_intersect_key($this->changed, $changedSet);
        if (true === empty($intersect)) {
            return null;
        }

        foreach ($intersect as $fieldName => $pattern) {
            $activity = $this->create($orderItem, $fieldName);
            if ('new' !== $fieldName) {
                $comment = $this->generateComment($fieldName, (string) $changedSet[$fieldName]['1']);
            } else {
                $comment = sprintf($this->changed['new'], $orderItem->getPhoto(), $orderItem->getComment());
            }
            $activity->setChangedComment($comment);

            return $activity;
        }

        return null;
    }

    /**
     * @param OrderItem $orderItem
     * @param string $fieldName
     *
     * @return Activity
     */
    protected function create(OrderItem $orderItem, string $fieldName): Activity
    {
        $activity = new Activity();
        $activity->setUser($this->getUser());
        $activity->setOrderItem($orderItem);
        $activity->setChanged($fieldName);

        return $activity;
    }

    /**
     * @param string $fieldName
     * @param string $newValue
     *
     * @return string
     */
    protected function generateComment(string $fieldName, string $newValue): string
    {
        if ('active' === $fieldName) {
            $newValue = '1' === $newValue ? 'Активный' : 'Не активный';
        }

        return sprintf($this->changed[$fieldName], $newValue);
    }

    /**
     * @return User|null
     */
    protected function getUser(): ?User
    {
        if (null === $token = $this->tokenStorage->getToken()) {
            return null;
        }

        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return null;
        }

        return $user;
    }
}
