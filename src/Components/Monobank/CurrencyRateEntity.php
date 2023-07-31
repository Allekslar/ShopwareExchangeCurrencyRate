<?php declare(strict_types=1);

namespace AleksWsdev\ObtainingExchangeCurrencyRate\Components\Monobank;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class CurrencyRateEntity extends Entity
{
    use EntityIdTrait;

    protected $jsonCurrencyData;

    public function getjsonCurrencyData(): ?array
    {
        return json_decode($this->jsonCurrencyData, true);
    }

    public function setjsonCurrencyData(array $jsonCurrencyData): void
    {
        $this->jsonCurrencyData = json_encode($jsonCurrencyData);
    }
}
