<?php declare(strict_types=1);

namespace AleksWsdev\ObtainingExchangeCurrencyRate\Administration;

use AleksWsdev\ObtainingExchangeCurrencyRate\Service\ApiCurrencyRateService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route(defaults: ['_routeScope' => ['api']])]
class ConfigController
{
    public function __construct(
        private readonly ApiCurrencyRateService $apiCurrencyRateService,
    ) {
    }

    #[Route(path: '/api/obtaining-exchange-rates/save-config', name: 'api.obtaining_exchange_rates.save_config', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $interval = $this->apiCurrencyRateService->intervalSettingApi()->invert;

        return new JsonResponse(['interval' => $interval]);
    }
}
