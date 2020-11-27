<?php

namespace App\Manager\AmoCRM;

use AmoCRM\AmoCRM\Client\AmoCRMApiClientFactory;
use AmoCRM\Client\AmoCRMApiClient;
use App\Client\AmoCRM\OAuthConfig;
use App\Client\AmoCRM\OAuthService;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class AmoCRMManager
{
    /** @var AmoCRMApiClient */
    public $client;

    /** @var null|string */
    private $code;

    /** @var OAuthConfig */
    private $authConfig;

    /** @var OAuthService */
    private $authService;

    public function __construct(OAuthConfig $authConfig, OAuthService $authService)
    {
        $this->authConfig = $authConfig;
        $this->authService = $authService;
        $this->createClient();
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function createClient(): void
    {
        $apiClientFactory = new AmoCRMApiClientFactory($this->authConfig, $this->authService);
        $this->client = $apiClientFactory->make();
        $this->client->setAccountBaseDomain("mywoodcard.amocrm.ru");

        $accessToken = $this->authService->getAccessToken();
        if (null === $accessToken && null !== $this->code) {
            $accessToken = $this->client->getOAuthClient()->getAccessTokenByCode($this->code);
        }

        if (null === $accessToken) {
            return;
        }

        if (false === $accessToken->hasExpired()) {
            $this->authService->saveOAuthToken($accessToken, $this->authService->getBaseDomain());
        }

        $this->client->setAccessToken($accessToken);
    }

    public function getOwner(): ResourceOwnerInterface
    {
        $accessToken = $this->authService->getAccessToken();
        if (null === $accessToken) {
            throw new \InvalidArgumentException('No amoCrm token');
        }

        return $this->client->getOAuthClient()->getResourceOwner($accessToken);
    }
}
