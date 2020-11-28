<?php

namespace App\Manager\AmoCRM;

use AmoCRM\AmoCRM\Client\AmoCRMApiClientFactory;
use AmoCRM\Client\AmoCRMApiClient;
use AmoCRM\Collections\CustomFieldsValuesCollection;
use AmoCRM\Filters\LinksFilter;
use AmoCRM\Models\CustomFieldsValues\TextCustomFieldValuesModel;
use AmoCRM\Models\CustomFieldsValues\ValueCollections\TextCustomFieldValueCollection;
use AmoCRM\Models\CustomFieldsValues\ValueModels\TextCustomFieldValueModel;
use AmoCRM\Models\LinkModel;
use App\Client\AmoCRM\OAuthConfig;
use App\Client\AmoCRM\OAuthService;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class AmoCRMManager
{
    const PRODUCT_CATALOG_ID = 5167;

    /** @var AmoCRMApiClient */
    public $client;

    /** @var null|string */
    private $code;

    /** @var OAuthConfig */
    private $authConfig;

    /** @var OAuthService */
    private $authService;

    public function __construct(OAuthConfig $authConfig, OAuthService $authService)
    {
        $this->authConfig = $authConfig;
        $this->authService = $authService;
        $this->createClient();
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function createClient(): void
    {
        $apiClientFactory = new AmoCRMApiClientFactory($this->authConfig, $this->authService);
        $this->client = $apiClientFactory->make();
        $this->client->setAccountBaseDomain("mywoodcard.amocrm.ru");

        $accessToken = $this->authService->getAccessToken();
        if (null === $accessToken && null !== $this->code) {
            $accessToken = $this->client->getOAuthClient()->getAccessTokenByCode($this->code);
        }

        if (null === $accessToken) {
            return;
        }

        if (false === $accessToken->hasExpired()) {
            $this->authService->saveOAuthToken($accessToken, $this->authService->getBaseDomain());
        }

        $this->client->setAccessToken($accessToken);
    }

    public function getOwner(): ResourceOwnerInterface
    {
        $accessToken = $this->authService->getAccessToken();
        if (null === $accessToken) {
            throw new \InvalidArgumentException('No amoCrm token');
        }

        return $this->client->getOAuthClient()->getResourceOwner($accessToken);
    }

    public function webhookProductsSync(int $lead): void
    {
        $leadsService = $this->client->leads();
        $catalogElementsService = $this->client->catalogElements(self::PRODUCT_CATALOG_ID);

        $leadModel = $leadsService->getOne($lead);

        $filters = new LinksFilter();
        $filters->setToEntityType('catalog_elements');
        $filters->setToCatalogId(self::PRODUCT_CATALOG_ID);

        $links = $leadsService->getLinks($leadModel, $filters);

        $products = [];
        $i = 0;
        /** @var LinkModel $link */
        foreach ($links->all() as $link) {
            $product = $catalogElementsService->getOne($link->getToEntityId());
            $skuField = $product->getCustomFieldsValues()->getBy('fieldCode', 'SKU');
            $sku = $skuField->getValues()->first()->getValue();
            $products[$i] = sprintf('%s*%d', $sku, $link->getMetadata()['quantity']);
            ++$i;
        }

        $productFiled = new TextCustomFieldValuesModel();
        $productFiled->setFieldId(334617);
        $productFiled->setValues(
            (new TextCustomFieldValueCollection())
                ->add(
                    (new TextCustomFieldValueModel())
                        ->setValue(implode(';', $products))
                )
        );

        if (null === $leadModel->getCustomFieldsValues()) {
            $leadModel->setCustomFieldsValues(new CustomFieldsValuesCollection());
        }
        $leadModel->getCustomFieldsValues()->add($productFiled);
        $leadsService->updateOne($leadModel);
    }
}
