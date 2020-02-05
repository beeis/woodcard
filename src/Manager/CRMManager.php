<?php

declare(strict_types=1);

namespace App\Manager;

use App\Client\CRMClientInterface;

/**
 * Class CRMManager
 *
 * @package App\Manager
 */
class CRMManager implements CRMManagerInterface
{
    /**
     * @var CRMClientInterface
     */
    private $client;

    /**
     * CRMManager constructor.
     *
     * @param CRMClientInterface $client
     */
    public function __construct(CRMClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function addNewOrder(array $options): array
    {
        return $this->client->post(
            'addNewOrder.html',
            [
                'order_id' => $options['order_id'],
                'country' => 'UA',
                'office' => 1,
                'products' => urlencode(serialize($options['products'] ?? null)),
                'bayer_name' => $options['name'],
                'phone' => $options['phone'],
                'email' => $options['email'] ?? null,
                'comment' => $options['comment'] ?? null,
                'site' => $options['site'] ?? null,
                'ip' => $options['ip'] ?? null,
                'delivery' => 1,
                'payment' => 2,
                'utm_source' => $options['utm_source'] ?? null,
                'utm_medium' => $options['utm_medium'] ?? null,
                'utm_term' => $options['utm_term'] ?? null,
                'utm_content' => $options['utm_content'] ?? null,
                'utm_campaign' => $options['utm_campaign'] ?? null,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getOrderByID(int $id): array
    {
        return $this->client->post(
            'getOrdersByID.html',
            [
                'order_id' => $id,
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getOrdersByID(array $ids): array
    {
        return $this->client->post(
            'getOrdersByID.html',
            [
                'order_id' => implode(',', $ids),
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getOrdersIdByStatus(int $status): array
    {
        return $this->client->post(
            'getOrdersIdByStatus.html',
            [
                'status' => $status,
            ]
        );
    }
}
