<?php

declare(strict_types=1);

namespace App\Manager;

/**
 * Interface CRMManagerInterface
 *
 * @package App\Manager
 */
interface CRMManagerInterface
{
    /**
     * @param array $options
     *
     * @return array
     */
    public function addNewOrder(array $options): array;

    /**
     * @param int $id
     *
     * @return array
     */
    public function getOrderByID(int $id): array;

    /**
     * {@inheritdoc}
     */
    public function getOrdersByID(array $ids): array;

    /**
     * @param int $status
     *
     * @return array
     */
    public function getOrdersIdByStatus(int $status): array;
}
