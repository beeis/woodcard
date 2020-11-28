<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Manager\OrderManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class OrderController
 *
 * @package App\Controller
 */
class OrderController extends Controller
{
    /**
     * @var OrderManagerInterface
     */
    private $orderManager;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->orderManager = $this->get('app.manager.amo_order_manager');
    }

    /**
     * @return Response
     *
     * @deprecated
     */
    public function index(): Response
    {
        $orders = $this->orderManager->list();

        return new JsonResponse($orders);
    }

    /**
     * @deprecated
     * @return Response
     */
    public function indexNew(): Response
    {
        return new JsonResponse([
            'data' => [],
        ]);

        $em = $this->getDoctrine()->getManager();

        /** @var OrderItem[] $orderItems */
        $orderItems = $em->getRepository(OrderItem::class)->findOrders(new \DateTime('2020-01-17'));

        /** @var Order[] $orders */
        $orders = $em->getRepository(Order::class)->getAllAndOrderByDESC();

        $items = [];

        foreach ($orders as $order) {
            if (true === $order->getItems()->isEmpty()) {
                $items[$order->getCreatedAt()->getTimestamp()] = [
                    'order_id' => $order->getNumber(),
                    'ttn_status' => '-',
                    'bayer_name' => $order->getName(),
                    'phone' => $order->getPhone(),
                    'created_at' => $order->getCreatedAt()->format('d/m/Y H:i:s'),
                    'has_item' => 'Нету',
                ];
            }
        }

        foreach ($orderItems as $orderItem) {
            $order = $orderItem->getOrder();
            $items[$orderItem->getCreatedAt()->getTimestamp()] = [
                'order_id' => $orderItem->getOrderId(),
                'ttn_status' => '-',
                'bayer_name' => $order ? $order->getName() : '-',
                'phone' => $order ? $order->getPhone() : '-',
                'created_at' => null !== $order ? $order->getCreatedAt()
                    ->format('d/m/Y H:i:s') : $orderItem->getCreatedAt()->format('d/m/Y H:i:s'),
                'has_item' => 'Есть',
            ];
        }
        rsort($items);

        return new JsonResponse([
            'data' => $items,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     *
     * Success response HTTP 200:
     * {
     *   "status":"ok",
     *   "data": {
     *       "order_id":{order_id},
     *       "country":{country},
     *       "site":{site},
     *       "ip":{ip}
     *   },
     *   "message":"Заказ успешно добавлен"
     * }
     *
     * Error response HTTP 200:
     * {
     *   "status":"error",
     *   "message":{error_text} //текстовое описание ошибки запроса
     * }
     */
    public function create(Request $request): Response
    {
        $files = $request->files->get('files', []);

        $order = $this->orderManager->create(
            [
                'name' => $request->request->get('name'),
                // products keys ['product_id', 'price', 'count']
                'products' => [
                    '1' => [
                        'product_id' => $request->request->get('product_id'),
                        'price' => $request->request->get('price'),
                        'count' => 1,
                    ],
                ],
                'phone' => $request->request->get('phone'),
                'email' => $request->request->get('email'),
                'comment' => $request->request->get('comment'),
                'site' => $request->getHost(),
                'ip' => $request->getClientIp(),
                'utm_source' => $request->request->get('utm_source'),
                'utm_medium' => $request->request->get('utm_medium'),
                'utm_term' => $request->request->get('utm_term'),
                'utm_content' => $request->request->get('utm_content'),
                'utm_campaign' => $request->request->get('utm_campaign'),
            ]
        );

        if (false === empty($files)) {
            $this->orderManager->createOrderItems($order, $files, $request->request->get('comment'));
        }

        if (true === $request->isXmlHttpRequest()) {
            return new JsonResponse([
                "status" => "ok",
                "data" => [
                    "order_id" => $order->getNumber(),
                ],
                "message" => "Заказ успешно добавлен",
            ], 201);
        }

        return $this->redirectToRoute('app_main_thankyoupage');
    }

    /**
     * @param Request $request
     * @param int $order
     *
     * @return Response
     */
    public function createItems(Request $request, int $order): Response
    {
        $files = $request->files->get('files');

        return new JsonResponse($this->orderManager->createItems($order, $files));
    }

    /**
     * @deprecated
     */
    public function createOrderWithItem(Request $request): Response
    {
        $files = $request->files->get('files', []);

        $order = $this->createOrderEntity([
            'name',
        ]);

        try {
            $order = $this->orderManager->create(
                [
                    'name' => $request->request->get('name'),
                    'products' => [
                        '1' => [
                            'product_id' => $request->request->get('product_id'),
                            'price' => $request->request->get('product_price'),
                            'count' => 1,
                        ],
                    ],
                    'phone' => $request->request->get('phone'),
                    'email' => $request->request->get('email'),
                    'comment' => $request->request->get('comment'),
                    'site' => $request->getHost(),
                    'ip' => $request->getClientIp(),
                    'utm_source' => $request->request->get('utm_source'),
                    'utm_medium' => $request->request->get('utm_medium'),
                    'utm_term' => $request->request->get('utm_term'),
                    'utm_content' => $request->request->get('utm_content'),
                    'utm_campaign' => $request->request->get('utm_campaign'),
                ]
            );
        } catch (\Exception $exception) {
        }


        if (true === isset($order['status']) && "error" === $order['status']) {
            return $this->redirectToRoute('app_main_heart2', ['error' => $order['message']]);
        }

//        $this->orderManager->createItems((int) $order['data'][0]['order_id'], $files);

        return $this->redirectToRoute('app_main_thankyoupage');
    }

    /**
     * @deprecated
     */
    public function createOrder(Request $request): Response
    {
        $files = $request->files->get('files', []);

        $order = $this->createOrderEntity($request->request->all());

        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();

        $this->orderManager->createOrderItems($order, $files,
            $request->request->get('product_id') == 5 ? 'Серце L 20 см' : 'Серце XXL 26 см');

        return $this->redirectToRoute('app_main_thankyoupage');
    }

    /**
     * @deprecated
     */
    public function createOrderNew(Request $request): Response
    {
        $data = json_decode($request->getContent(), true);

        $order = $this->createOrderEntity($data);

        $em = $this->getDoctrine()->getManager();
        $em->persist($order);
        $em->flush();

        return new JsonResponse([
            'order_id' => $order->getNumber(),
            'bayer_name' => $order->getName(),
            'phone' => $order->getPhone(),
        ]);
    }

    /**
     * @deprecated
     */
    private function createOrderEntity(array $data): Order
    {
        $order = new Order();

        $number = false === empty($data['number']) ? $data['number'] : number_format(round(microtime(true) * 10), 0,
            '.', '');

        $order->setNumber($number);
        $order->setName($data['name']);
        $order->setPhone($data['phone']);

        return $order;
    }
}
