<?php

declare(strict_types=1);

namespace App\Manager;

use Symfony\Component\HttpFoundation\File\UploadedFile;

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
     * @param int $orderId
     * @param array|UploadedFile[] $files
     *
     * @return array
     */
    public function createItems(int $orderId, array $files): array;

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
