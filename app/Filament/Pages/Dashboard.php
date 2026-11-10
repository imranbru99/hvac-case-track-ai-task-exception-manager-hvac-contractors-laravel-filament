<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\CaseStatsOverview;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\AccountWidget;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            CaseStatsOverview::class,
            AccountWidget::class,
        ];
    }
}
