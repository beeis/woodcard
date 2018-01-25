<?php

declare(strict_types=1);

namespace App\Storage;

use Imagine\Image\ImageInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Interface FileStorageInterface
 *
 * @package App\Storage
 */
interface FileStorageInterface
{
    /**
     * @param string $prefix
     *
     * @return array
     */
    public function listKeys(string $prefix = ''): array;

    /**
     * @param UploadedFile $file
     * @param string $filename
     *
     * @return bool
     */
    public function upload(UploadedFile $file, string $filename): bool;

    /**
     * @param ImageInterface $image
     * @param string $filename
     *
     * @return bool
     */
    public function uploadImage(ImageInterface $image, string $filename): bool;

    /**
     * @param string $filename
     *
     * @return bool
     */
    public function remove(string $filename): bool;
}
