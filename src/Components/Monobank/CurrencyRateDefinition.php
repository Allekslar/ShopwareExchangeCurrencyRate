<?php declare(strict_types=1);

namespace AleksWsdev\ObtainingExchangeCurrencyRate\Components\Monobank;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\ApiAware;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\JsonField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class CurrencyRateDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'aleks_currency_rate';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return CurrencyRateEntity::class;
    }

    public function getCollectionClass(): string
    {
        return CurrencyRateCollection::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new ApiAware(), new PrimaryKey(), new Required()),
            (new JsonField('json_currency_data', 'jsonCurrencyData'))->addFlags(new ApiAware(), new Required()),
        ]);
    }
}
