<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Activity;
use App\Entity\OrderItem;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ActivityController
 *
 * @package App\Controller
 */
class ActivityController extends Controller
{
    /**
     * @param int $orderItem
     *
     * @return Response
     */
    public function index(int $orderItem): Response
    {
        $orderItemRepository = $this->getDoctrine()->getRepository(OrderItem::class);
        $orderItem = $orderItemRepository->find($orderItem);
        if (null === $orderItem) {
            throw $this->createNotFoundException();
        }

        $activityRepository = $this->getDoctrine()->getRepository(Activity::class);
        $activities = $activityRepository->findBy(['orderItem' => $orderItem]);
        $result = [];
        /** @var Activity $activity */
        foreach ($activities as $activity) {
            $result[] = [
                'user' => null === $activity->getUser() ? null : $activity->getUser()->getUsername(),
                'changed' => $activity->getChanged(),
                'changed_comment' => preg_replace(
                    '/{image_base}/',
                    'https://s3.eu-central-1.amazonaws.com/woodcard',
                    $activity->getChangedComment()
                ),
                'created_at' => $activity
                    ->getCreatedAt()
                    ->setTimezone(new \DateTimeZone('Europe/Kiev'))
                    ->format('H:i:s d-m-Y'),
                'updated_at' => $activity
                    ->getUpdatedAt()
                    ->setTimezone(new \DateTimeZone('Europe/Kiev'))
                    ->format('H:i:s d-m-Y'),
            ];
        }

        return new JsonResponse($result);
    }
}
