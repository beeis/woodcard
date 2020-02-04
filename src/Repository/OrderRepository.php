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
        $qb = $this->createQueryBuilder('order');

        $qb->select('order');

        return $qb->getQuery()->getResult();
    }
}
