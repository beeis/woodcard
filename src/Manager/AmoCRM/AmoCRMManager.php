<?php

namespace App\Manager\AmoCRM;

use AmoCRM\AmoCRM\Client\AmoCRMApiClientFactory;
use AmoCRM\Client\AmoCRMApiClient;
use App\Client\AmoCRM\OAuthConfig;
use App\Client\AmoCRM\OAuthService;
use App\Client\CRMClientInterface;
use App\Manager\CRMManagerInterface;

class AmoCRMManager
{
    /** @var AmoCRMApiClient */
    public $client;

    /**
     * CRMManager constructor.
     *
     * @param CRMClientInterface $client
     */
    public function __construct(OAuthConfig $authConfig, OAuthService $authService)
    {
        $apiClientFactory = new AmoCRMApiClientFactory($authConfig, $authService);
        $this->client = $apiClientFactory->make();
        $this->client->setAccountBaseDomain("mywoodcard.amocrm.ru");

        $accessToken = $authService->getAccessToken();
        if (null === $accessToken) {
            $accessToken = $this->client->getOAuthClient()->getAccessTokenByCode("def50200ab09154dbb4a70f5b78eefc999b34fc46a3e3a4fa68cf16449734f653d62b69f33f143b928fd85cc5fd870952e58d1228ddfed3d06ae74b5761e5fd65f4201599bbdb50fdeb0ff19ab4d750bd817fff748e68f26f2b3aee5c94856b6656d3aad624c8b6adcf5ca81d4ac5451980df0edbe033f7e529cf0f8c9da50ff1fab6a84582c880e8559c5b8bc98d154faf38c515c6081daa30d8a714465bd33c3d2b71937da51cd9eecfba6cfac21a5d5e4301d9be91d903280039deba4977511f2b6d00e707fa223dbef45d94e11938c3517d959506df129eb823d7fee71eb7c962f594cc1cc1adbe0f8dc47e1edda3021fa414fe3a59b6095981bfb70afe872ee402d8c09fff39b319f00dd48808f3b66cba79af22302597e31317532c80bfda8ec64da4d13ad3f2592707dfac3bb9e9caa35970a6e5e994f8e16eb285dbcb4792a8804688f556978746caffbefe46b04e491ceb556ef972179736a59b10307b5c378dc592365fcda4a34df3d85c7b286e9c159ee6bbd30ba3788fb530ca4dac6781182f9e6db4b4b2ae8ea0e64a4225d64a12f24149d980aaa517162a22756776cedcda91d1334e711f8affa0fe3f66b876b75f621cc4ff7a8125e90da6b15");
        }

        if (false === $accessToken->hasExpired()) {
            $authService->saveOAuthToken($accessToken, $authService->getBaseDomain());
        }

        $this->client->setAccessToken($accessToken);
    }
}
