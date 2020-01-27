<?php

declare(strict_types=1);

namespace App\Controller;

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
        $this->orderManager = $this->get('app.manager.order_manager');
    }

    /**
     * @return Response
     */
    public function index(): Response
    {
        $orders = $this->orderManager->list();

        return new JsonResponse($orders);
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
        return new JsonResponse(
            $this->orderManager->create(
                [
                    'name' => $request->request->get('name'),
                    // products keys ['product_id', 'price', 'count']
                    'products' => $request->request->get('products', []),
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
            )
        );
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

    public function createOrderWithItem(Request $request): Response
    {
        $files = $request->files->get('files', []);

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


        if ("error" === $order['status']) {
            return $this->redirectToRoute('app_main_heart2', ['error' => $order['message']]);
        }

        $this->orderManager->createItems((int) $order['data'][0]['order_id'], $files);

        return $this->redirectToRoute('app_main_thankyoupage');
    }
}
