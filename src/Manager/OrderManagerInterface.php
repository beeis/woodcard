<?php

declare(strict_types=1);

namespace App\Manager;

/**
 * Interface OrderManagerInterface
 *
 * @package App\Manager
 */
interface OrderManagerInterface
{
    /**
     * @param array $options
     *
     * @return array
     */
    public function create(array $options): array;

    /**
     * @return array
     */
    public function list(): array;

    /**
     * @param int $id
     *
     * @return array
     */
    public function get(int $id): array;
}
