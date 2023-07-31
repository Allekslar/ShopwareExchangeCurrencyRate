<?php

declare(strict_types=1);

namespace AleksWsdev\ObtainingExchangeCurrencyRate\Service;

use Shopware\Core\Framework\Adapter\Cache\CacheCompressor;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class CurrencyRateService
{
    final public const KEY = 'currency-rate';
    private const ID = '68d26072a57f4e2d8b044aec8edd9f48';
    private const CACHE_EXPIRATION = 300;

    private $cacheKey;

    public function __construct(
        private readonly EntityRepository $currencyRateEntityRepository,
        private readonly ApiCurrencyRateService $apiCurrencyRateService,
        private readonly AdapterInterface $cache,
    ) {
        $this->cacheKey = self::ID;
    }

    public function saveJsonCurrencyData(array $jsonCurrencyData, Context $context): void
    {
        if ($this->apiCurrencyRateService->intervalSettingApi()->invert === 1) {
            try {
                $this->currencyRateEntityRepository->upsert([
                    [
                        'id' => self::ID,
                        'jsonCurrencyData' => $jsonCurrencyData,
                    ],
                ], $context);
            } catch (\Exception $e) {
                // Handle the exception
            }
        }
    }

    public function baseCurrency(): array
    {
        return $this->apiCurrencyRateService->getBaseCurrency();
    }

    public function updateCurrencyData(): bool
    {
        $filterCurrencyData = $this->apiCurrencyRateService->filterCurrencyData();

        if ($filterCurrencyData === null) {
            return false;
        }

        $this->saveJsonCurrencyData($filterCurrencyData, Context::createDefaultContext());
        $this->clearCache();
        $this->addCache();

        return true;
    }

    public function clearCache(): void
    {
        $this->cache->deleteItem($this->cacheKey);
    }

    public function getCache()
    {
        $cacheItem = $this->cache->getItem($this->cacheKey);
        $cachedData = CacheCompressor::uncompress($cacheItem);
        if (!$cachedData) {
            return;
        }

        return json_decode($cachedData);
    }

    public function addCache(): void
    {
        $cacheItem = $this->cache->getItem($this->cacheKey);

        $exchangeRateData = $this->apiCurrencyRateService->exchangeRateData();

        if (!$exchangeRateData) {
            return;
        }
        $arrayExchangeRateData = $exchangeRateData->jsonCurrencyData;

        $data = json_encode($arrayExchangeRateData);
        $cacheItem = CacheCompressor::compress($cacheItem, $data);
        $cacheItem->expiresAfter(self::CACHE_EXPIRATION);

        $this->cache->save($cacheItem);
    }

    public function getCurrencyData(): ?array
    {
        if ($this->cache->hasItem($this->cacheKey)) {
            return $this->getCache();
        }

        $this->addCache();

        return $this->getCache();
    }
}
