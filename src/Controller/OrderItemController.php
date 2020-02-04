<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

/**
 * Class OrderItemController
 *
 * @package App\Controller
 */
class OrderItemController extends Controller
{
    /**
     * @param int $order
     *
     * @return Response
     */
    public function index(int $order): Response
    {
        $order = $this->get('app.manager.order_manager')->get($order);
        if (false === isset($order['data']['order_id'])) {
            throw $this->createNotFoundException();
        }

        $orderItemRepository = $this->getDoctrine()->getRepository(OrderItem::class);
        $items = $orderItemRepository->findBy(['orderId' => $order['data']['order_id']]);
        $itemsResult = [];
        foreach ($items as $item) {
            $itemsResult[] = $this->viewOrderItem($item);
        }

        return new JsonResponse(
            [
                'order' => $order,
                'items' => $itemsResult,
            ]
        );
    }
    /**
     * @param int $order
     *
     * @return Response
     */
    public function indexNew(int $orderId): Response
    {
        $orderRepository = $this->getDoctrine()->getRepository(Order::class);

        /** @var Order $order */
        $order = $orderRepository->findOneBy(['number' => $orderId]);

        $orderItemRepository = $this->getDoctrine()->getRepository(OrderItem::class);
        $items = $orderItemRepository->findBy(['orderId' => $orderId]);
        $itemsResult = [];
        foreach ($items as $item) {
            $itemsResult[] = $this->viewOrderItem($item);
        }

        return new JsonResponse(
            [
                'order' => $this->viewOrder($order, $orderId),
                'items' => $itemsResult,
            ]
        );
    }

    /**
     * @param Request $request
     * @param int $order
     *
     * request.file
     * request.comment
     *
     * @return Response
     */
    public function create(Request $request, int $order): Response
    {
        $order = $this->get('app.manager.order_manager')->get($order);
        if (false === isset($order['data']['order_id'])) {
            throw $this->createNotFoundException();
        }

        $orderItem = new OrderItem();
        $orderItem->setOrderId($order['data']['order_id']);
        $orderItem->setComment($request->request->get('comment'));
        $filename = $this->get('app.manager.file_manager')
            ->uploadPhoto($request->files->get('file'), (int) $order['data']['order_id']);
        $orderItem->setPhoto($filename);

        $em = $this->getDoctrine()->getManager();
        $em->persist($orderItem);
        $em->flush();

        return new JsonResponse($this->viewOrderItem($orderItem));
    }

    /**
     * @param Request $request
     * @param int $orderItem
     *
     * @return Response
     */
    public function active(Request $request, int $orderItem): Response
    {
        $bodyParams = json_decode($request->getContent(), true);
        $orderItemRepository = $this->getDoctrine()->getRepository(OrderItem::class);
        $orderItem = $orderItemRepository->find($orderItem);
        if (null === $orderItem) {
            throw $this->createNotFoundException();
        }
        $orderItem->setActive((bool) $bodyParams['active']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($orderItem);
        $em->flush();

        return new JsonResponse($this->viewOrderItem($orderItem));
    }

    /**
     * @param Request $request
     * @param int $orderItem
     *
     * @return Response
     */
    public function comment(Request $request, int $orderItem): Response
    {
        $bodyParams = json_decode($request->getContent(), true);
        $orderItemRepository = $this->getDoctrine()->getRepository(OrderItem::class);
        $orderItem = $orderItemRepository->find($orderItem);
        if (null === $orderItem) {
            throw $this->createNotFoundException();
        }
        $orderItem->setComment($bodyParams['comment']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($orderItem);
        $em->flush();

        return new JsonResponse($this->viewOrderItem($orderItem));
    }

    /**
     * @param Request $request
     * @param int $orderItem
     *
     * @return Response
     */
    public function inscription(Request $request, int $orderItem): Response
    {
        $bodyParams = json_decode($request->getContent(), true);
        $orderItemRepository = $this->getDoctrine()->getRepository(OrderItem::class);
        $orderItem = $orderItemRepository->find($orderItem);
        if (null === $orderItem) {
            throw $this->createNotFoundException();
        }
        $orderItem->setInscription($bodyParams['inscription']);

        $em = $this->getDoctrine()->getManager();
        $em->persist($orderItem);
        $em->flush();

        return new JsonResponse($this->viewOrderItem($orderItem));
    }

    /**
     * @param Request $request
     * @param int $orderItem
     *
     * @return Response
     */
    public function uploadModel(Request $request, int $orderItem): Response
    {
        $orderItemRepository = $this->getDoctrine()->getRepository(OrderItem::class);
        $orderItem = $orderItemRepository->find($orderItem);
        if (null === $orderItem) {
            throw $this->createNotFoundException();
        }

        $filename = $this->get('app.manager.file_manager')
            ->uploadModel($request->files->get('file'), (int) $orderItem->getOrderId());
        $orderItem->setModel($filename);
        $filename = $this->get('app.manager.file_manager')
            ->uploadPrint($request->files->get('file'), (int) $orderItem->getOrderId());
        $orderItem->setPrint($filename);

        $em = $this->getDoctrine()->getManager();
        $em->persist($orderItem);
        $em->flush();

        return new JsonResponse($this->viewOrderItem($orderItem));
    }

    /**
     * @param Request $request
     * @param int $orderItem
     *
     * @return Response
     */
    public function uploadPsd(Request $request, int $orderItem): Response
    {
        $orderItemRepository = $this->getDoctrine()->getRepository(OrderItem::class);
        $orderItem = $orderItemRepository->find($orderItem);
        if (null === $orderItem) {
            throw $this->createNotFoundException();
        }

        $filename = $this->get('app.manager.file_manager')
            ->uploadPsd($request->files->get('file'), (int) $orderItem->getOrderId());
        $orderItem->setPsd($filename);

        $em = $this->getDoctrine()->getManager();
        $em->persist($orderItem);
        $em->flush();

        return new JsonResponse($this->viewOrderItem($orderItem));
    }

    /**
     * @param int $orderItem
     *
     * @return Response
     */
    public function duplicate(int $orderItem): Response
    {
        $orderItemRepository = $this->getDoctrine()->getRepository(OrderItem::class);
        $orderItem = $orderItemRepository->find($orderItem);
        if (null === $orderItem) {
            throw $this->createNotFoundException();
        }

        $newOrderItem = clone $orderItem;
        $em = $this->getDoctrine()->getManager();
        $em->persist($newOrderItem);
        $em->flush();

        return new JsonResponse($this->viewOrderItem($orderItem));
    }

    /**
     * @param int $orderItem
     *
     * @return Response
     */
    public function downloadPrint(int $orderItem): Response
    {
        $orderItemRepository = $this->getDoctrine()->getRepository(OrderItem::class);
        $orderItem = $orderItemRepository->find($orderItem);
        if (null === $orderItem) {
            throw $this->createNotFoundException();
        }

        $order = $this->get('app.manager.order_manager')->get((int) $orderItem->getOrderId());
        if (false === isset($order['data']['order_id'])) {
            throw $this->createNotFoundException();
        }

        if (false === $this->get('app.storage.file_storage')->exist($orderItem->getPrint())) {
            throw $this->createNotFoundException();
        }
        $file = $this->get('app.storage.file_storage')->get($orderItem->getPrint());

        $filename = sprintf(
            '%s-%s-%s.%s',
            $orderItem->getOrderId(),
            $orderItem->getId(),
            $order['data']['bayer_name'],
            'jpg'
        );

        $headers = [
            'Content-Type' => 'your_content_type',
            'Content-Description' => 'File Transfer',
            'Content-Disposition' => 'attachment; filename='.$filename,
            'filename' => $filename,
        ];

        return new Response($file, 200, $headers);
    }

    /**
     * @param OrderItem $orderItem
     *
     * @return array
     */
    private function viewOrderItem(OrderItem $orderItem): array
    {
        return [
            'id' => $orderItem->getId(),
            'order_id' => $orderItem->getOrderId(),
            'comment' => $orderItem->getComment(),
            'inscription' => $orderItem->getInscription(),
            'active' => $orderItem->isActive(),
            'photo' => $orderItem->getPhoto(),
            'psd' => $orderItem->getPsd(),
            'model' => $orderItem->getModel(),
            'print' => $orderItem->getPrint(),
            'created_at' => $orderItem
                ->getCreatedAt()
                ->setTimezone(new \DateTimeZone('Europe/Kiev'))
                ->format('H:i:s d-m-Y'),
            'updated_at' => $orderItem
                ->getUpdatedAt()
                ->setTimezone(new \DateTimeZone('Europe/Kiev'))
                ->format('H:i:s d-m-Y'),
        ];
    }

    /**
     * @param OrderItem $orderItem
     *
     * @return array
     */
    private function viewOrder(?Order $order, $orderId): array
    {
        return [
            'data' => [
                'order_id' =>  $order ? $order->getNumber() : $orderId,
                'bayer_name' => $order ? $order->getName() : '- -',
                'phone' => $order ? $order->getPhone() : '- -',
            ]
        ];
    }
}
