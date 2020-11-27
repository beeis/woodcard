<?php

namespace App\Client\AmoCRM;

use AmoCRM\OAuth\OAuthServiceInterface;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OAuthService implements OAuthServiceInterface
{

    /** @var Filesystem */
    private $filesystem;

    /** @var string */
    private $path;

    public function __construct(Filesystem $filesystem, string $path) {
        $this->filesystem = $filesystem;
        $this->path = $path;
    }

    public function saveOAuthToken(AccessTokenInterface $accessToken, string $baseDomain): void
    {
        if (true === $this->filesystem->exists($this->path)) {
            $this->filesystem->remove($this->path);
        }

        $this->filesystem->touch($this->path);
        $this->filesystem->appendToFile($this->path, serialize([
            'accessToken' => $accessToken,
            'baseDomain' => $baseDomain
        ]));
    }

    public function getAccessToken(): ?AccessTokenInterface
    {
        if (false === $this->filesystem->exists($this->path)) {
            return null;
        }
        $content = file_get_contents($this->path);
        if (false === $content) {
            return null;
        }
        $result = unserialize($content);

        return $result['accessToken'];
    }

    public function getBaseDomain(): string
    {
        if (false === $this->filesystem->exists($this->path)) {
            return "";
        }
        $content = file_get_contents($this->path);
        if (false === $content) {
            return "";
        }
        $result = unserialize($content);

        return $result['baseDomain'] ?? "";
    }
}
