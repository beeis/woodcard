<?php

declare(strict_types=1);

namespace App\Manager;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Interface FileManagerInterface
 *
 * @package App\Manager
 */
interface FileManagerInterface
{
    /**
     * @param UploadedFile $file
     * @param int $orderId
     *
     * @return null|string
     */
    public function uploadPhoto(UploadedFile $file, int $orderId): ?string;

    /**
     * @param UploadedFile $file
     * @param int $orderId
     *
     * @return null|string
     */
    public function uploadModel(UploadedFile $file, int $orderId): ?string;

    /**
     * @param UploadedFile $file
     * @param int $orderId
     *
     * @return null|string
     */
    public function uploadPsd(UploadedFile $file, int $orderId): ?string;

    /**
     * @param string $filename
     *
     * @return bool
     */
    public function remove(string $filename): bool;
}
