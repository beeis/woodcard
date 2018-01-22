<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Activity;
use App\Entity\OrderItem;

/**
 * Interface ActivityManagerInterface
 *
 * @package App\Manager
 */
interface ActivityManagerInterface
{
    /**
     * @param OrderItem $orderItem
     * @param array $changedSet
     *
     * @return Activity|null
     */
    public function handleChanges(OrderItem $orderItem, array $changedSet = []): ?Activity;
}
