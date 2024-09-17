<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use App\Models\Department;
use Filament\Widgets\ChartWidget;

class DasAdminInfoWidgets extends ChartWidget
{
    protected static ?int $sort = 1;
   // protected static ?string $maxHeight ='1000px';
    protected int|string|array $columnSpan = 'half'; // Adjusted to fit side by side

    protected static ?string $heading = 'Total Appointments According to Departments';

    protected static array $colors = [
        'rgb(153, 102, 255)',
        'rgb(255, 159, 64)',
        'rgb(255, 100, 71)',
        'rgb(0,255,0)',
        'rgb(0, 255, 255)',
        'rgb(255, 20, 147)',
        'rgb(255, 69, 0)',
        'rgb(32, 178, 170)',
        'rgb(135, 206, 250)',
        'rgb(255, 155, 180)',
        'rgb(186, 85, 211)',
        'rgb(0, 128, 128)',
        'rgb(128, 0, 128)',
        'rgb(255, 0, 0)',
        'rgb(0, 0, 255)',
        'rgb(0, 254, 0)',
        'rgb(180, 189, 255)',
    ];

    protected function getData(): array
    {
        // Fetch departments with at least one appointment
        $departments = Department::whereHas('appointments')
            ->withCount('appointments')
            ->whereNotNull('name')
            ->get();

        $departmentLabels = $departments->pluck('name')->toArray();
        $departmentData = $departments->pluck('appointments_count')->toArray();

        // Ensure we have enough colors for the number of departments
        $colors = array_map(function ($index) {
            return self::$colors[$index % count(self::$colors)];
        }, array_keys($departmentLabels));

        return [
            'labels' => $departmentLabels,
            'datasets' => [
                [
                    'label' => 'Appointments by Department',
                    'data' => $departmentData,
                    'backgroundColor' => $colors,
                    'hoverOffset' => 6,
                ],
            ],
            'options' => [
                'maintainAspectRatio' => false,
                'height' => 400, // Adjust pie chart height here
            ],
        ];
    }

    public static function canView(): bool
    {
        return auth()->user()->role === 'admin';
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
