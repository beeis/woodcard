<?php

declare(strict_types=1);

namespace App\Manager;

use App\Storage\FileStorageInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class FileManager
 *
 * @package App\Manager
 */
class FileManager implements FileManagerInterface
{
    const DIR_ORIGINAL_PHOTO = 'original';
    const DIR_ORIGINAL_MODEL = 'model';
    const DIR_ORIGINAL_PSD = 'psd';

    /**
     * @var FileStorageInterface
     */
    private $fileStorage;

    /**
     * FileManager constructor.
     *
     * @param FileStorageInterface $fileStorage
     */
    public function __construct(FileStorageInterface $fileStorage)
    {
        $this->fileStorage = $fileStorage;
    }

    /**
     * {@inheritdoc}
     */
    public function uploadPhoto(UploadedFile $file, int $orderId): ?string
    {
        return $this->upload($file, $orderId, self::DIR_ORIGINAL_PHOTO);
    }

    /**
     * {@inheritdoc}
     */
    public function uploadModel(UploadedFile $file, int $orderId): ?string
    {
        return $this->upload($file, $orderId, self::DIR_ORIGINAL_MODEL);
    }

    /**
     * {@inheritdoc}
     */
    public function uploadPsd(UploadedFile $file, int $orderId): ?string
    {
        return $this->upload($file, $orderId, self::DIR_ORIGINAL_PSD);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(string $filename): bool
    {
        return $this->fileStorage->remove($filename);
    }

    /**
     * @param UploadedFile $file
     * @param int $orderId
     * @param string $dir
     *
     * @return null|string
     */
    protected function upload(UploadedFile $file, int $orderId, string $dir): ?string
    {
        $filename = sprintf('%s/%s/%s.%s', $orderId, $dir, md5(uniqid().time()), $file->guessExtension());

        if (true === $this->fileStorage->upload($file, $filename)) {
            return $filename;
        }

        return null;
    }

}
