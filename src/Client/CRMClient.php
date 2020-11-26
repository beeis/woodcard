<?php

declare(strict_types=1);

namespace App\Client;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

/**
 * Class EnvyBoxClient
 *
 * @package App\Client
 */
class CRMClient implements CRMClientInterface
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var string
     */
    private $apiKey;

    /**
     * EnvyBoxClient constructor.
     *
     * @param ClientInterface $client
     * @param string $apiKey
     */
    public function __construct(ClientInterface $client, string $apiKey)
    {
        $this->client = $client;
        $this->apiKey = $apiKey;
    }

    /**
     * @param string $uri
     * @param array $arguments
     *
     * @return null|array
     */
    public function post(string $uri, array $arguments = []): ?array
    {
        try {
            $response = $this->client->request(
                'POST',
                sprintf('api/%s', $uri),
                [
                    RequestOptions::CONNECT_TIMEOUT => 10,
                    RequestOptions::TIMEOUT => 10,
                    RequestOptions::FORM_PARAMS => array_merge(
                        [
                            'key' => $this->apiKey
                        ],
                        $arguments
                    ),
                ]
            );

            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $exception) {
            return null;
        }
    }
}
