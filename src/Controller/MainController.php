<?php

declare(strict_types=1);

namespace App\Controller;

use AmoCRM\Models\ContactModel;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Manager\AmoCRM\AmoCRMManager;
use App\Manager\AmoCRM\AmoOrderManager;
use App\Manager\OrderManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class MainController
 *
 * @package App\Controller
 */
class MainController extends Controller implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    /** @var AmoCRMManager */
    private $amoCRMManager;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        $this->amoCRMManager = $this->get('app.manager.amo_crm_manager');
    }

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
    public function order(int $orderNumber): Response
    {
        $orderItemRepository = $this->getDoctrine()->getRepository(OrderItem::class);

        try {
            $order = $this->get('app.manager.order_manager')->get($orderNumber);
        } catch (\Exception $exception) {
            $orderRepository = $this->getDoctrine()->getRepository(Order::class);
            /** @var Order $orderEntity */
            $orderEntity = $orderRepository->findOneBy(['number' => $orderNumber]);
            $order = [
                'data' => [
                    'total' => '-',
                    'order_id' => $orderEntity ? $orderEntity->getNumber() : $orderNumber,
                    'bayer_name' =>  $orderEntity ? $orderEntity->getName() : '-',
                ]
            ];
        }

        $items = $orderItemRepository->findActiveItems($orderNumber);

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
    public function heart2(Request $request): Response
    {
        return $this->render(
            'main/heart2.html.twig',
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

    public function webhook(Request $request): JsonResponse
    {
        $this->logger->critical('2-[WEBHOOK]', $request->request->all() ?? []);

        $leadId = isset($request->request->get('leads')['update']['id']) ? $request->request->get('leads')['update']['id'] :  null;
        $linksChanged = isset($request->request->get('leads')['update']['link_changed']) ? true : false;

        if (null !== $leadId && false !== $linksChanged) {
            $this->logger->critical('[WEBHOOK]-2.0', [$leadId]);
            $this->amoCRMManager->webhookProductsSync((int) $leadId);
        }

        return new JsonResponse();
    }
}
