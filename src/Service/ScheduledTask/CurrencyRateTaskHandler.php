<?php declare(strict_types=1);

namespace AleksWsdev\ObtainingExchangeCurrencyRate\Service\ScheduledTask;

use AleksWsdev\ObtainingExchangeCurrencyRate\Service\CurrencyRateService;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\DataAbstractionLayer\EntityRepository;
use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTask;
use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTaskDefinition;
use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTaskEntity;
use Shopware\Core\Framework\MessageQueue\ScheduledTask\ScheduledTaskHandler;
use Shopware\Core\System\SystemConfig\SystemConfigService;

class CurrencyRateTaskHandler extends ScheduledTaskHandler
{
    public function __construct(private readonly CurrencyRateService $currencyRateService, protected EntityRepository $scheduledRepository, private readonly SystemConfigService $systemConfigService)
    {
        parent::__construct($scheduledRepository);
    }

    public static function getHandledMessages(): iterable
    {
        return [CurrencyRateTask::class];
    }

    public function run(): void
    {
        $this->currencyRateService->updateCurrencyData();
    }

    protected function rescheduleTask(ScheduledTask $task, ScheduledTaskEntity $taskEntity): void
    {
        $now = new \DateTimeImmutable();
        $newNextExecutionTime = $now->modify(sprintf('+%d seconds', $this->getRunInterval()));

        if ($newNextExecutionTime < $now) {
            $newNextExecutionTime = $now;
        }

        $this->scheduledTaskRepository->update([
            [
                'id' => $task->getTaskId(),
                'status' => ScheduledTaskDefinition::STATUS_SCHEDULED,
                'lastExecutionTime' => $now,
                'nextExecutionTime' => $newNextExecutionTime,
            ],
        ], Context::createDefaultContext());
    }

    protected function getRunInterval(): ?int
    {
        return $this->systemConfigService->get('ObtainingExchangeCurrencyRate.config.interval');
    }
}
