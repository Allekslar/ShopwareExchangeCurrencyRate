<?php declare(strict_types=1);

namespace AleksWsdev\ObtainingExchangeCurrencyRate\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1686317498CurrencyRate extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1686317498;
    }

    public function update(Connection $connection): void
    {
        $query = <<<SQL
        CREATE TABLE `aleks_currency_rate` (
        `id` BINARY(16) NOT NULL,
        `json_currency_data` JSON NOT NULL,
        `created_at` DATETIME(3) NOT NULL,
        `updated_at` DATETIME(3) NULL,
        PRIMARY KEY (`id`),
        CONSTRAINT `json.aleks_currency_rate.json_currency_data` CHECK (JSON_VALID(`json_currency_data`))
        )
        ENGINE=InnoDB
        DEFAULT CHARSET=utf8mb4
        COLLATE=utf8mb4_unicode_ci;
        SQL;

        $connection->executeStatement($query);
    }

    public function updateDestructive(Connection $connection): void
    {
        // implement update destructive
    }
}
