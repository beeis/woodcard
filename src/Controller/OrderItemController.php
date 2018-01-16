<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\OrderItem;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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

        return $this->render(
            'orderItem/index.html.twig',
            [
                'order' => $order,
                'items' => $items,
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
        $orderItem->setCreatedAt(new \DateTime());
        $orderItem->setUpdatedAt(new \DateTime());
        $filename = $this->get('app.manager.file_manager')
            ->uploadPhoto($request->files->get('file'), (int) $order['data']['order_id']);
        $orderItem->setPhoto($filename);

        $em = $this->getDoctrine()->getManager();
        $em->persist($orderItem);
        $em->flush();

        return new JsonResponse(
            [
                'order_id' => $orderItem->getOrderId(),
                'comment' => $orderItem->getComment(),
                'active' => $orderItem->isActive(),
                'photo' => $orderItem->getPhoto(),
                'psd' => $orderItem->getPsd(),
                'model' => $orderItem->getModel(),
                'created_at' => $orderItem->getCreatedAt(),
                'updated_at' => $orderItem->getUpdatedAt(),
            ]
        );
    }

    /**
     * @param Request $request
     * @param int $orderItem
     *
     * @return Response
     */
    public function active(Request $request, int $orderItem): Response
    {
        $active = (bool) $request->request->get('active', false);
        $orderItemRepository = $this->getDoctrine()->getRepository(OrderItem::class);
        $orderItem = $orderItemRepository->find($orderItem);
        if (null === $orderItem) {
            throw $this->createNotFoundException();
        }
        $orderItem->setActive($active);
        $orderItem->setUpdatedAt(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($orderItem);
        $em->flush();

        return new JsonResponse(
            [
                'order_id' => $orderItem->getOrderId(),
                'comment' => $orderItem->getComment(),
                'active' => $orderItem->isActive(),
                'photo' => $orderItem->getPhoto(),
                'psd' => $orderItem->getPsd(),
                'model' => $orderItem->getModel(),
                'created_at' => $orderItem->getCreatedAt(),
                'updated_at' => $orderItem->getUpdatedAt(),
            ]
        );
    }

    /**
     * @param Request $request
     * @param int $orderItem
     *
     * @return Response
     */
    public function comment(Request $request, int $orderItem): Response
    {
        $comment = $request->request->get('comment');
        $orderItemRepository = $this->getDoctrine()->getRepository(OrderItem::class);
        $orderItem = $orderItemRepository->find($orderItem);
        if (null === $orderItem) {
            throw $this->createNotFoundException();
        }
        $orderItem->setComment($comment);
        $orderItem->setUpdatedAt(new \DateTime());

        $em = $this->getDoctrine()->getManager();
        $em->persist($orderItem);
        $em->flush();

        return new JsonResponse(
            [
                'order_id' => $orderItem->getOrderId(),
                'comment' => $orderItem->getComment(),
                'active' => $orderItem->isActive(),
                'photo' => $orderItem->getPhoto(),
                'psd' => $orderItem->getPsd(),
                'model' => $orderItem->getModel(),
                'created_at' => $orderItem->getCreatedAt(),
                'updated_at' => $orderItem->getUpdatedAt(),
            ]
        );
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
        $orderItem->setUpdatedAt(new \DateTime());

        $filename = $this->get('app.manager.file_manager')
            ->uploadPhoto($request->files->get('file'), (int) $orderItem->getOrderId());
        $orderItem->setModel($filename);

        $em = $this->getDoctrine()->getManager();
        $em->persist($orderItem);
        $em->flush();

        return new JsonResponse(
            [
                'order_id' => $orderItem->getOrderId(),
                'comment' => $orderItem->getComment(),
                'active' => $orderItem->isActive(),
                'photo' => $orderItem->getPhoto(),
                'psd' => $orderItem->getPsd(),
                'model' => $orderItem->getModel(),
                'created_at' => $orderItem->getCreatedAt(),
                'updated_at' => $orderItem->getUpdatedAt(),
            ]
        );
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
        $orderItem->setUpdatedAt(new \DateTime());

        $filename = $this->get('app.manager.file_manager')
            ->uploadPsd($request->files->get('file'), (int) $orderItem->getOrderId());
        $orderItem->setPsd($filename);

        $em = $this->getDoctrine()->getManager();
        $em->persist($orderItem);
        $em->flush();

        return new JsonResponse(
            [
                'order_id' => $orderItem->getOrderId(),
                'comment' => $orderItem->getComment(),
                'active' => $orderItem->isActive(),
                'photo' => $orderItem->getPhoto(),
                'psd' => $orderItem->getPsd(),
                'model' => $orderItem->getModel(),
                'created_at' => $orderItem->getCreatedAt(),
                'updated_at' => $orderItem->getUpdatedAt(),
            ]
        );
    }
}
