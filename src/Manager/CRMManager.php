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
                'order_id' => number_format(round(microtime(true) * 10), 0, '.', ''),
                'country' => 'UA',
                'office' => 1,
                'products' => urlencode(serialize($options['products'])),
                'bayer_name' => $options['name'],
                'phone' => $options['phone'],
                'email' => $options['email'],
                'comment' => $options['comment'],
                'site' => $options['site'],
                'ip' => $options['ip'],
                'delivery' => 1,
                'payment' => 2,
                'utm_source' => $options['utm_source'],
                'utm_medium' => $options['utm_medium'],
                'utm_term' => $options['utm_term'],
                'utm_content' => $options['utm_content'],
                'utm_campaign' => $options['utm_campaign'],
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
