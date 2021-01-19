<?php

namespace App\Manager\AmoCRM;

use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Collections\LinksCollection;
use AmoCRM\Exceptions\AmoCRMApiNoContentException;
use AmoCRM\Filters\ContactsFilter;
use AmoCRM\Models\CatalogElementModel;
use AmoCRM\Models\ContactModel;
use AmoCRM\Models\CustomFieldsValues\MultitextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\MultitextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\MultitextCustomFieldValueModel;
use AmoCRM\Models\LeadModel;
use AmoCRM\OAuth2\Client\Provider\AmoCRMException;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Manager\CRMManager;
use App\Manager\FileManagerInterface;
use App\Manager\OrderManagerInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class AmoOrderManager implements OrderManagerInterface
{
    const PIPELINE_ID = 2307442;

    /** @var AmoCRMManager */
    private $amoCRMManager;

    /** @var EntityManagerInterface  */
    private $entityManager;

    /** @var FileManagerInterface */
    private $fileManager;

    public function __construct(
        AmoCRMManager $amoCRMManager,
        EntityManagerInterface $entityManager,
        FileManagerInterface $fileManager
    )
    {
        $this->amoCRMManager = $amoCRMManager;
        $this->entityManager = $entityManager;
        $this->fileManager = $fileManager;
    }

    public function create(array $options): Order
    {
        $leadsService = $this->amoCRMManager->client->leads();

        $contactModel = $this->getContactModel($options['name'], $options['phone']);
        $product = $this->getProduct((int) $options['products']['1']['product_id']);

        $lead = new LeadModel();
        $lead->setPipelineId(self::PIPELINE_ID);
        $lead->setName($options['name']);

        try {
            $lead = $leadsService->addOne($lead);
            $links = new LinksCollection();
            $links->add($contactModel);
            $links->add($product);
            $leadsService->link($lead, $links);
            $leadsService->link($lead, $links);
        } catch (\Exception $exception) {
            throw new BadRequestHttpException('Спробуйте знову або напишіть нам у вабйер.');
        }

        $order = new Order();
        $order->setName($options['name']);
        $order->setPhone($options['phone']);
        $order->setNumber((string) $lead->getId());
        $this->entityManager->persist($order);
        $this->entityManager->flush();

        return $order;
    }

    public function createItems(int $orderId, array $files): array
    {
        return [];
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

    public function list(): array
    {
        return [];
    }

    public function get(int $id): array
    {
        $leadsService = $this->amoCRMManager->client->leads();
        $contactsService = $this->amoCRMManager->client->contacts();

        /** @var LeadModel $leadModel */
        $leadModel = $leadsService->getOne($id);
        $links = $leadsService->getLinks($leadModel);
        $link = $links->getBy('toEntityType', 'contacts');
        if (null === $link) {
            return [
                'data' => [
                    'order_id' => $leadModel->getId(),
                    'bayer_name' => $leadModel->getName(),
                    'phone' => "",
                    'price'=> '-',
                ]
            ];
        }

        $contactModel = $contactsService->getOne($link->getToEntityId());

        $phone = "";
        if (false === $contactModel->getCustomFieldsValues()->getBy('fieldCode', 'PHONE')->getValues()->isEmpty()) {
            $phone = $contactModel->getCustomFieldsValues()->getBy('fieldCode', 'PHONE')->getValues()->first()->getValue();
        }

        return [
            'data' => [
                'order_id' => (string) $leadModel->getId(),
                'bayer_name' => $leadModel->getName(),
                'phone' => $phone,
                'price'=> $leadModel->getPrice(),
            ]
        ];
    }

    private function getContactModel(string $name, string $phoneNumber): ContactModel
    {
        $contactsService = $this->amoCRMManager->client->contacts();
        $filter = new ContactsFilter();
        try {
            $contactModelCollection = $contactsService->get($filter->setQuery($phoneNumber));
            $contactModel = $contactModelCollection->first();
        } catch (AmoCRMApiNoContentException $exception) {
            $contactModel = new ContactModel();
            $contactModel->setName($name);
        }

        $phoneField = (new MultitextCustomFieldValuesModel())->setFieldCode('PHONE');
        $phoneField->setValues(
            (new MultitextCustomFieldValueCollection())
                ->add(
                    (new MultitextCustomFieldValueModel())
                        ->setEnum('MOB')
                        ->setValue($phoneNumber)
                )
        );

        $contactCustomFieldsValues = new CustomFieldsValuesCollection();
        $contactCustomFieldsValues->add($phoneField);
        $contactModel->setCustomFieldsValues($contactCustomFieldsValues);
        if (null === $contactModel->getId()) {
            $contactsService->addOne($contactModel);
        } else {
            $contactsService->updateOne($contactModel);
        }

        return $contactModel;
    }

    private function getProduct(int $productId): CatalogElementModel
    {
        $catalogElementService = $this->amoCRMManager->client->catalogElements(5167);

        return $catalogElementService->getOne($productId);
    }
}
