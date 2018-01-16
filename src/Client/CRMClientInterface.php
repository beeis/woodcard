<?php

declare(strict_types=1);

namespace App\Client;

/**
 * Interface CRMClientInterface
 *
 * @package App\Client
 */
interface CRMClientInterface
{
    /**
     * @param string $uri
     * @param array $arguments
     *
     * @return null|array
     */
    public function post(string $uri, array $arguments = []): ?array;
}
