<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\OrderItem;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MainController
 *
 * @package App\Controller
 */
class MainController extends Controller
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        return $this->render(
            'main/index.html.twig'
        );
    }

    /**
     * @param int $order
     *
     * @return Response
     */
    public function order(int $order): Response
    {
        $orderItemRepository = $this->getDoctrine()->getRepository(OrderItem::class);
        $items = $orderItemRepository->findActiveItems($order);
        $order = $this->get('app.manager.order_manager')->get($order);

        return $this->render(
            'main/order.html.twig',
            [
                'items' => $items,
                'order' => $order['data']
            ]
        );
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function heart(Request $request): Response
    {
        return $this->render(
            'main/heart.html.twig',
            [
                'utm_source' => $request->get('utm_source'),
                'utm_medium' => $request->get('utm_medium'),
                'utm_term' => $request->get('utm_term'),
                'utm_content' => $request->get('utm_content'),
                'utm_campaign' => $request->get('utm_campaign'),
            ]
        );
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function toy(Request $request): Response
    {
        return $this->render(
            'main/toy.html.twig',
            [
                'utm_source' => $request->get('utm_source'),
                'utm_medium' => $request->get('utm_medium'),
                'utm_term' => $request->get('utm_term'),
                'utm_content' => $request->get('utm_content'),
                'utm_campaign' => $request->get('utm_campaign'),
            ]
        );
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function flower(Request $request): Response
    {
        return $this->render(
            'main/flower.html.twig',
            [
                'utm_source' => $request->get('utm_source'),
                'utm_medium' => $request->get('utm_medium'),
                'utm_term' => $request->get('utm_term'),
                'utm_content' => $request->get('utm_content'),
                'utm_campaign' => $request->get('utm_campaign'),
            ]
        );
    }

    /**
     * @return Response
     */
    public function thankyoupage(): Response
    {
        return $this->render('main/thankyoupage.html.twig');
    }

    /**
     * @return Response
     */
    public function policy(): Response
    {
        return $this->render('main/policy.html.twig');
    }
}
