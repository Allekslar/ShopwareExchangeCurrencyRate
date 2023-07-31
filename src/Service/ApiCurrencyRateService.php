<?php

declare(strict_types=1);

namespace AleksWsdev\ObtainingExchangeCurrencyRate\Service;

use Shopware\Administration\Notification\NotificationService;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\System\SystemConfig\SystemConfigService;
use Symfony\Component\HttpClient\HttpClient;

class ApiCurrencyRateService
{
    private const ID = '68d26072a57f4e2d8b044aec8edd9f48';
    private const HTTP_METHOD = 'GET';
    private const CURRENCY_CODE_B = 980;
    private const CURRENCY_FILE = __DIR__ . '/../Resources/config/data.json';
    private const INTERVAL_TIMEOUT = 5;

    private ?Context $context = null;

    public function __construct(
        private readonly EntityRepository $currencyRateEntityRepository,
        private readonly SystemConfigService $systemConfigService,
        private readonly NotificationService $notificationService
    ) {
    }

    public function getCurrencyDataFromApi(): ?array
    {
        $client = HttpClient::create();
        $endpoint = $this->systemConfigService->get('ObtainingExchangeCurrencyRate.config.endpoint');

        if (!($this->intervalSettingApi()->invert === 1)) {
            $status = 'warning';
            $message = $this->intervalSettingApi()->format('You can try again %i minutes  %s secondss');

            $this->notificationService->createNotification(
                [
                    'status' => $status,
                    'message' => $message,
                    'adminOnly' => true,
                ],
                Context::createDefaultContext()
            );

            return null;
        }

        if ($this->intervalSettingApi()->invert === 1) {
            try {
                $response = $client->request(self::HTTP_METHOD, $endpoint);

                return $response->toArray();
            } catch (\ErrorException $e) {
                // Handle the exception
            } catch (\Exception $e) {
                // Handle the exception
            }
        }

        return null;
    }

    public function intervalSettingApi(): \DateInterval
    {
        $currencyDataTime = $this->exchangeRateData();
        $currentTime = new \DateTimeImmutable();
        if (!$currencyDataTime) {
            $interval = $currentTime->diff($currentTime->modify('-1 minutes'));

            return $interval;
        }

        $lastCurrencyDataTime = $currencyDataTime->updatedAt ?? $currencyDataTime->createdAt;
        $nextTime = $lastCurrencyDataTime->modify(sprintf('+%d minutes', self::INTERVAL_TIMEOUT));

        $interval = $currentTime->diff($nextTime);

        return $interval;
    }

    public function filterCurrencyData(): ?array
    {
        $data = $this->getCurrencyDataFromApi();

        if ($data === null) {
            return null;
        }

        $currencyCode = $this->systemConfigService->get('ObtainingExchangeCurrencyRate.config.currencyCode');

        $arrayFilter = array_filter(
            $data,
            fn ($item) => \in_array($item['currencyCodeA'], $currencyCode, false) && ($item['currencyCodeB'] === self::CURRENCY_CODE_B)
        );

        $jsonCurrencyData = $this->getSettingsFile();
        $combinedArray = [];

        foreach ($jsonCurrencyData as $item1) {
            $number = $item1['number'];

            foreach ($arrayFilter as $item2) {
                $currencyCodeA = $item2['currencyCodeA'];

                if ($number === $currencyCodeA) {
                    $combinedArray[] = array_merge($item1, $item2);
                }
            }
        }

        return array_reverse($combinedArray);
    }

    public function getSettingsFile(): array
    {
        $jsonData = file_get_contents(self::CURRENCY_FILE);

        return json_decode($jsonData, true) ?? [];
    }

    public function getBaseCurrency()
    {
        $jsonCurrencyData = $this->getSettingsFile();

        $baseCurrency = array_filter(
            $jsonCurrencyData,
            fn ($item) => ($item['number'] === self::CURRENCY_CODE_B)
        );

        return array_values($baseCurrency)[0] ?? null;
    }

    public function exchangeRateData()
    {
        $exchangeRateData = $this->currencyRateEntityRepository
            ->search(new Criteria([self::ID]), Context::createDefaultContext())
            ->first();

        return $exchangeRateData;
    }
}
