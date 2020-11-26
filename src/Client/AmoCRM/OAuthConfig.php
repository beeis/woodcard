<?php

namespace App\Client\AmoCRM;

use AmoCRM\OAuth\OAuthConfigInterface;

class OAuthConfig implements OAuthConfigInterface
{
    /** @var string */
    private $integrationId;

    /** @var string */
    private $secretKey;

    /** @var string */
    private $redirectDomain;

    public function __construct(string $integrationId, string $secretKey, string $redirectDomain)
    {
        $this->integrationId = $integrationId;
        $this->secretKey = $secretKey;
        $this->redirectDomain = $redirectDomain;
    }

    public function getIntegrationId(): string
    {
        return $this->integrationId;
    }

    public function getSecretKey(): string
    {
        return $this->secretKey;
    }

    public function getRedirectDomain(): string
    {
        return $this->redirectDomain;
    }
}
