<?php

declare(strict_types=1);

namespace App\Manager;

use App\Storage\FileStorageInterface;
use Imagine\Image\ImageInterface;
use Imagine\Image\ImagineInterface;
use Symfony\Component\HttpFoundation\File\File;
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
    const DIR_ORIGINAL_PRINT = 'print';

    /**
     * @var FileStorageInterface
     */
    private $fileStorage;
    /**
     * @var ImagineInterface
     */
    private $imagine;

    /**
     * FileManager constructor.
     *
     * @param FileStorageInterface $fileStorage
     * @param ImagineInterface $imagine
     */
    public function __construct(
        FileStorageInterface $fileStorage,
        ImagineInterface $imagine
    )
    {
        $this->fileStorage = $fileStorage;
        $this->imagine = $imagine;
    }

    public function getFile(string $filename)
    {
        $file = file_get_contents('https://s3.eu-central-1.amazonaws.com/woodcard2/'.$filename);
        return new File($file);
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
    public function uploadPrint(UploadedFile $file, int $orderId): ?string
    {
        $image = $this->imagine->open($file->getPathname());
        $image->flipHorizontally();

        return $this->uploadImage($image, $orderId, self::DIR_ORIGINAL_PRINT);
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
        $filename = sprintf('%s/%s/%s.%s', $orderId, $dir, md5(uniqid().time()), $file->getClientOriginalExtension());

        if (true === $this->fileStorage->upload($file, $filename)) {
            return $filename;
        }

        return null;
    }

    /**
     * @param ImageInterface $image
     * @param int $orderId
     * @param string $dir
     *
     * @return null|string
     */
    protected function uploadImage(ImageInterface $image, int $orderId, string $dir): ?string
    {
        $filename = sprintf('%s/%s/%s.%s', $orderId, $dir, md5(uniqid().time()), 'jpg');

        if (true === $this->fileStorage->uploadImage($image, $filename)) {
            return $filename;
        }

        return null;
    }
}
