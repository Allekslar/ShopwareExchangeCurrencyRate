<?php declare(strict_types=1);

namespace AleksWsdev\ObtainingExchangeCurrencyRate\Components\Monobank;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

class CurrencyRateCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return CurrencyRateEntity::class;
    }
}
