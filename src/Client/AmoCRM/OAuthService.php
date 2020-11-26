<?php

namespace App\Client\AmoCRM;

use AmoCRM\OAuth\OAuthServiceInterface;
use League\OAuth2\Client\Token\AccessTokenInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class OAuthService implements OAuthServiceInterface
{
    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(SessionInterface $session) {
        $this->session = $session;
    }

    public function saveOAuthToken(AccessTokenInterface $accessToken, string $baseDomain): void
    {
        $this->session->set('accessToken', $accessToken);
        $this->session->set('baseDomain', $baseDomain);
    }

    public function getAccessToken(): ?AccessTokenInterface
    {
        return $this->session->get('accessToken');
    }

    public function getBaseDomain(): string
    {
        return $this->session->get('baseDomain', "");
    }
}
