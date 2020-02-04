<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Class OrderRepository
 *
 * @package App\Repository
 */
class OrderRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function findOrdersWithoutItems(\DateTime $fromDate): array
    {
        $qb = $this->createQueryBuilder('o');

        $qb->select('o');

        return $qb->getQuery()->getResult();
    }
    /**
     * @return array
     */
    public function getAllAndOrderByDESC(): array
    {
        $qb = $this->createQueryBuilder('o');

        $qb->orderBy('o.createdAt', 'DESC');

        return $qb->getQuery()->getResult();
    }
}
