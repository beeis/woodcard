<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class OrderItemRepository
 *
 * @package App\Repository
 */
class OrderItemRepository extends EntityRepository
{
    /**
     * @param int $orderId
     *
     * @return array
     */
    public function findActiveItems(int $orderId): array
    {
        $qb = $this->createQueryBuilder('orderItem');

        $qb
            ->where('orderItem.orderId = :orderId')
            ->andWhere($qb->expr()->isNotNull('orderItem.model'))
            ->andWhere('orderItem.active = true')
            ->setParameter('orderId', $orderId);

        return $qb->getQuery()->getResult();
    }
}
