<?php declare(strict_types=1);

namespace AleksWsdev\ObtainingExchangeCurrencyRate\DataResolver;

use AleksWsdev\ObtainingExchangeCurrencyRate\Service\CurrencyRateService;
use Shopware\Core\Content\Cms\Aggregate\CmsSlot\CmsSlotEntity;
use Shopware\Core\Content\Cms\DataResolver\CriteriaCollection;
use Shopware\Core\Content\Cms\DataResolver\Element\AbstractCmsElementResolver;
use Shopware\Core\Content\Cms\DataResolver\Element\ElementDataCollection;
use Shopware\Core\Content\Cms\DataResolver\ResolverContext\ResolverContext;
use Shopware\Core\Framework\Struct\ArrayStruct;
use Shopware\Core\Framework\Struct\Struct;

class CurrencyRateCmsElementResolver extends AbstractCmsElementResolver
{
    public function __construct(private readonly CurrencyRateService $currencyRateService)
    {
    }

    public function getType(): string
    {
        return 'currency-rate';
    }

    public function collect(CmsSlotEntity $slot, ResolverContext $resolverContext): ?CriteriaCollection
    {
        return null;
    }

    public function enrich(CmsSlotEntity $slot, ResolverContext $resolverContext, ElementDataCollection $result): void
    {
        $currencyDataArray = $this->currencyRateService->getCurrencyData();

        if (empty($currencyDataArray)) {
            return;
        }
        $baseCurrencyDataArray = $this->currencyRateService->baseCurrency();

        $arrayStructData = $this->getStruct()->assign($currencyDataArray);
        $arrayStructData->addArrayExtension('baseCurrency', $baseCurrencyDataArray);

        $slot->setData($arrayStructData);
    }

    public function getStruct(): Struct
    {
        return new ArrayStruct();
    }
}
