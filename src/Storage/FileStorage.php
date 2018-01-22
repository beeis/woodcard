<?php

declare(strict_types=1);

namespace App\Storage;

use Gaufrette\Adapter;
use Gaufrette\Adapter\AwsS3;
use Gaufrette\Filesystem;
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
     * FileStorage constructor.
     *
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
        $this->adapter = $this->filesystem->getAdapter();
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
        return (bool) $this->adapter->write($filename, file_get_contents($file->getPathname()));
    }

    /**
     * {@inheritdoc}
     */
    public function remove(string $filename): bool
    {
        return $this->adapter->delete($filename);
    }
}
