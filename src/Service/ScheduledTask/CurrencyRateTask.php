<?php declare(strict_types=1);

namespace AleksWsdev\ObtainingExchangeCurrencyRate\Service\ScheduledTask;

use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTask;

class CurrencyRateTask extends ScheduledTask
{
    public static function getTaskName(): string
    {
        return 'aleks_currency_rate_task';
    }

    public static function getDefaultInterval(): int
    {
        return 300;
    }
}
