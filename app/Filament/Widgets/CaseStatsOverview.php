<?php

namespace App\Filament\Widgets;

use App\Models\CaseException;
use App\Models\CaseMessage;
use App\Models\Escalation;
use App\Models\Task;
use App\Services\ModuleManager;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CaseStatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $modules = app(ModuleManager::class);

        return [
            Stat::make('Pending Approval', CaseMessage::query()->where('status', 'pending_approval')->count())
                ->description('AI-drafted tasks awaiting human review')
                ->color('warning'),
            Stat::make('Open SLA Tasks', $modules->enabled('sla_tasks')
                ? Task::query()->whereIn('status', ['open', 'in_progress', 'blocked'])->count()
                : 0)
                ->description('Active assigned work')
                ->color('success'),
            Stat::make('Open Exceptions', $modules->enabled('exception_manager')
                ? CaseException::query()->whereIn('status', ['open', 'investigating', 'escalated'])->count()
                : 0)
                ->description('SLA / ops exceptions')
                ->color('danger'),
            Stat::make('Pending Escalations', $modules->enabled('escalation_engine')
                ? Escalation::query()->where('status', 'pending')->count()
                : 0)
                ->description('Awaiting owner ack')
                ->color('warning'),
        ];
    }
}
