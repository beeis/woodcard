<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\Order;
use App\Entity\OrderItem;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * OrderManager constructor.
     *
     * @param CRMManager $CRMManager
     * @param EntityManagerInterface $entityManager
     * @param FileManagerInterface $fileManager
     */
    public function __construct(
        LoggerInterface $logger,
        CRMManager $CRMManager,
        EntityManagerInterface $entityManager,
        FileManagerInterface $fileManager
    ) {
        $this->CRMManager = $CRMManager;
        $this->entityManager = $entityManager;
        $this->fileManager = $fileManager;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options): Order
    {
        $options = $this->getOrGenerateOrderId($options);

        $order = $this->createOrderEntity($options);
        try {
            $crmOrder = $this->CRMManager->addNewOrder($options);
        } catch (\Exception $exception) {
            $this->logger->critical('lp.crm.create_order.failed');
        }

        if (true === isset($crmOrder['status']) && self::RESPONSE_ERROR === $crmOrder['status']) {
            $this->logger->emergency('lp.crm.create_order.has_error', ['text' => $crmOrder['message']]);
        }

        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $order;
    }

    /**
     * {@inheritdoc}
     */
    public function list(): array
    {
        $newOrders = $this->CRMManager->getOrdersIdByStatus(self::ORDER_STATUS_MODEL_IN_PROGRESS);
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
     * @deprecated
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
            $orderItem->setOrderId((string)$orderId);
            $this->entityManager->persist($orderItem);
        }
        $this->entityManager->flush();

        return $items;
    }

    /**
     * {@inheritdoc}
     */
    public function createOrderItems(Order $order, array $files, string $comment = null): array
    {
        $items = [];
        foreach ($files as $file) {
            $filename = $this->fileManager->uploadPhoto($file, (int)$order->getNumber());
            if (null === $filename) {
                continue;
            }
            $items[] = $filename;
            $orderItem = new OrderItem();
            $orderItem->setPhoto($filename);
            $orderItem->setOrderId((string)$order->getNumber());
            $orderItem->setOrder($order);
            $orderItem->setComment($comment);
            $this->entityManager->persist($orderItem);
        }
        $this->entityManager->flush();

        return $items;
    }

    private function getOrGenerateOrderId(array $options): array
    {
        $options['order_id'] = true === isset($options['order_id']) ? $options['order_id'] : number_format(round(microtime(true) * 10), 0, '.', '');

        return $options;
    }

    private function createOrderEntity(array $data): Order
    {
        $order = new Order();

        $number = false === empty($data['order_id']) ? $data['order_id'] : number_format(round(microtime(true) * 10), 0,
            '.', '');

        $order->setNumber($number);
        $order->setName($data['name']);
        $order->setPhone($data['phone']);

        return $order;
    }
}
