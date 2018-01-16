<?php

declare(strict_types=1);

namespace App\Controller;

use App\Manager\OrderManagerInterface;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;
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

        // replace this line with your own code!
        return $this->render(
            'order/index.html.twig',
            [
                'orders' => $orders,
            ]
        );
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
//        var_dump($request->request->get('products', []));exit;
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
}
