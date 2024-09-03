<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class DasAdminInfoWidgets extends BaseWidget
{
    protected static string $view = 'filament.widgets.das-admin-info-widgets';
    public static function canView(): bool
    {
        return auth()->user()->role === 'admin';
    }
}
