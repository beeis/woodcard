<?php

declare(strict_types=1);

namespace App\Storage;

use Gaufrette\Adapter;
use Gaufrette\Adapter\AwsS3;
use Gaufrette\Filesystem;
use Imagine\Image\ImageInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class FileStorage
 *
 * @package App\Storage
 */
class FileStorage implements FileStorageInterface
{
    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @var Adapter|AwsS3
     */
    private $adapter;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * FileStorage constructor.
     *
     * @param Filesystem $filesystem
     * @param LoggerInterface $logger
     */
    public function __construct(
        Filesystem $filesystem,
        LoggerInterface $logger
    )
    {
        $this->filesystem = $filesystem;
        $this->adapter = $this->filesystem->getAdapter();
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function listKeys(string $prefix = ''): array
    {
        return $this->adapter->listKeys($prefix);
    }

    /**
     * {@inheritdoc}
     */
    public function upload(UploadedFile $file, string $filename): bool
    {
        if (false === $file->isValid()) {
            $this->logger->error($file->getErrorMessage());

            return false;
        }

        return (bool) $this->adapter->write($filename, file_get_contents($file->getPathname()));
    }

    /**
     * {@inheritdoc}
     */
    public function uploadImage(ImageInterface $image, string $filename): bool
    {
        return (bool) $this->adapter->write($filename, $image->get('jpg'));
    }

    /**
     * {@inheritdoc}
     */
    public function remove(string $filename): bool
    {
        return $this->adapter->delete($filename);
    }
}
