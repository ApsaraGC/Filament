<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class DasDoctorInfoWidgets extends ChartWidget
{
    protected static ?int $sort = 2;
    //protected static ?string $maxHeight = '600px';
    protected int|string|array $columnSpan = 'half'; // Adjust as needed

    protected static ?string $heading = 'Your Appointments by Date';

    protected static array $colors = [

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
        $doctorId = auth()->user()->doctor->id ?? '';

        // Define the date range for the past 7 days
        $today = Carbon::today();
        $startDate = $today->copy()->subDays(7); // Last 7 days

        // Fetch appointment counts by date for the last 7 days
        $appointments = Appointment::where('doctor_id', $doctorId)
            ->whereBetween('date_time', [$startDate, $today])
            ->selectRaw('DATE(date_time) as date, COUNT(*) as count')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Prepare data for the chart
        $dates = $appointments->pluck('date')->toArray();
        $counts = $appointments->pluck('count')->toArray();

        // Ensure we have enough colors for the number of dates
        $colors = array_map(function ($index) {
            return self::$colors[$index % count(self::$colors)];
        }, array_keys($dates));

        return [
            'labels' => $dates,
            'datasets' => [
                [
                    'label' => 'Appointments by Date',
                    'data' => $counts,
                    'backgroundColor' => $colors,
                    'hoverOffset' => 6,
                    'barThickness'=> 50,
                ],
            ],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
    protected static function getView(): string
    {
        return 'filament.widgets.das-doctor-info-widgets';
    }
    public static function canView(): bool
    {
        return auth()->user()->role === 'doctor';
    }
}
