<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\OrderItem;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Class OrderManager
 *
 * @package App\Manager
 */
class OrderManager implements OrderManagerInterface
{
    const RESPONSE_SUCCESS = 'ok';
    const RESPONSE_ERROR = 'error';

    const ORDER_STATUS_NEW = 3;
    const ORDER_STATUS_MODEL_IN_PROGRESS = 32;
    const ORDER_STATUS_EDITS = 33;

    /**
     * @var CRMManager
     */
    private $CRMManager;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var FileManagerInterface
     */
    private $fileManager;

    /**
     * OrderManager constructor.
     *
     * @param CRMManager $CRMManager
     * @param EntityManagerInterface $entityManager
     * @param FileManagerInterface $fileManager
     */
    public function __construct(
        CRMManager $CRMManager,
        EntityManagerInterface $entityManager,
        FileManagerInterface $fileManager
    )
    {
        $this->CRMManager = $CRMManager;
        $this->entityManager = $entityManager;
        $this->fileManager = $fileManager;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options): array
    {
        return $this->CRMManager->addNewOrder($options);
    }

    /**
     * {@inheritdoc}
     */
    public function list(): array
    {
        $newOrders = $this->CRMManager->getOrdersIdByStatus(self::ORDER_STATUS_NEW);
        $editsOrders = $this->CRMManager->getOrdersIdByStatus(self::ORDER_STATUS_EDITS);

        if (self::RESPONSE_ERROR === $newOrders['status']) {
            $newOrders = ['data' => []];
        }

        if (self::RESPONSE_ERROR === $editsOrders['status']) {
            $editsOrders = ['data' => []];
        }

        $orders = array_merge($newOrders['data'], $editsOrders['data']);
        if (true === empty($orders)) {
            return [];
        }

        sort($orders, SORT_NUMERIC);

        return $this->CRMManager->getOrdersByID($orders);
    }

    /**
     * {@inheritdoc}
     */
    public function get(int $id): array
    {
        return $this->CRMManager->getOrderByID($id);
    }

    /**
     * {@inheritdoc}
     */
    public function createItems(int $orderId, array $files): array
    {
        $items = [];
        foreach ($files as $file) {
            $filename = $this->fileManager->uploadPhoto($file, $orderId);
            if (null === $filename) {
                continue;
            }
            $items[] = $filename;
            $orderItem = new OrderItem();
            $orderItem->setPhoto($filename);
            $orderItem->setOrderId((string) $orderId);
            $this->entityManager->persist($orderItem);
        }
        $this->entityManager->flush();

        return $items;
    }
}
