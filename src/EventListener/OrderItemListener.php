<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Activity;
use App\Entity\OrderItem;
use App\Manager\ActivityManagerInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;

/**
 * Class OrderItemListener
 *
 * @package App\EventListener
 */
class OrderItemListener implements EventSubscriber
{
    /**
     * @var ActivityManagerInterface
     */
    private $activityManager;

    /**
     * OrderItemListener constructor.
     *
     * @param ActivityManagerInterface $activityManager
     */
    public function __construct(ActivityManagerInterface $activityManager)
    {
        $this->activityManager = $activityManager;
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        /** @var OrderItem $orderItem */
        $orderItem = $eventArgs->getObject();
        if (false === $orderItem instanceof OrderItem) {
            return;
        }

        try {
            $activity = $this->activityManager->handleChanges($orderItem, ['new' => []]);
            $eventArgs->getEntityManager()->persist($activity);
            $eventArgs->getEntityManager()->flush();
        } catch (\Exception $exception) {
            var_dump($exception->getMessage());
            exit;
        }
    }

    /**
     * @param OnFlushEventArgs $eventArgs
     *
     * @throws \Doctrine\ORM\ORMException
     */
    public function onFlush(OnFlushEventArgs $eventArgs)
    {
        $em = $eventArgs->getEntityManager();
        $uow = $em->getUnitOfWork();

        /** @var OrderItem $orderItem */
        foreach ($uow->getScheduledEntityUpdates() as $orderItem) {
            if (false === $orderItem instanceof OrderItem) {
                return;
            }
            $activity = $this->activityManager->handleChanges($orderItem, $uow->getEntityChangeSet($orderItem));
            $em->persist($activity);
            $metaData = $em->getClassMetadata(Activity::class);
            $uow->computeChangeSet($metaData, $activity);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSubscribedEvents()
    {
        return ['postPersist', 'onFlush'];
    }
}
