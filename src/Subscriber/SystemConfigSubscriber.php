<?php

declare(strict_types=1);

namespace AleksWsdev\ObtainingExchangeCurrencyRate\Subscriber;

use AleksWsdev\ObtainingExchangeCurrencyRate\Service\CurrencyRateService;
use Shopware\Core\System\SystemConfig\Event\SystemConfigChangedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SystemConfigSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private readonly CurrencyRateService $currencyRateService,
    ) {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SystemConfigChangedEvent::class => 'onSystemConfigChanged',
        ];
    }

    public function onSystemConfigChanged(SystemConfigChangedEvent $event): void
    {
        if ($event->getKey() !== 'ObtainingExchangeCurrencyRate.config.currencyCode') {
            return;
        }

        $this->currencyRateService->updateCurrencyData();
    }
}
