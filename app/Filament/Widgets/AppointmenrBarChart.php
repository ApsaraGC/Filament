<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Widgets\ChartWidget;

class AppointmenrBarChart extends ChartWidget
{
    protected static ?int $sort = 3;
    protected int| string | array $columnSpan = 'full'; // Adjusted to fit side by side

   // protected static string $color = 'info';
  protected static ?string $maxHeight = '500px';



    protected static ?string $heading = 'Appointments by Month';

    protected static array $colors = [
        'rgb(255, 99, 132)',
        'rgb(54, 162, 235)',
        'rgb(255, 205, 86)',
        'rgb(75, 192, 192)',
        'rgb(153, 102, 255)',
        'rgb(255, 159, 64)',
        'rgb(255, 100, 71)',
        'rgb(0,255,0)',
        'rgb(255, 69, 0)',
        'rgb(32, 178, 170)',
        'rgb(135, 206, 250)',
        'rgb(255, 155, 180)',
        'rgb(186, 85, 211)',
        'rgb(0, 128, 128)',
        'rgb(128, 0, 128)',
        'rgb(255, 0, 0)',
    ];

    protected function getData(): array
    {
        $appointments = Appointment::selectRaw("strftime('%Y-%m', date_time) as month_year, COUNT(*) as count")
            ->groupBy('month_year')
            ->orderBy('month_year')
            ->get();

        $months = [
            '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April', '05' => 'May', '06' => 'June',
            '07' => 'July', '08' => 'August', '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
        ];

        // Transform month_year into readable format
        $appointmentLabels = $appointments->map(function ($appointment) use ($months) {
            $month = substr($appointment->month_year, 5, 2); // Extract month from 'YYYY-MM'
            $year = substr($appointment->month_year, 0, 4); // Extract year from 'YYYY-MM'
            return $months[$month] . ' ' . $year;
        })->toArray();

        $appointmentData = $appointments->pluck('count')->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Appointments by Month',
                    'data' => $appointmentData,
                    'backgroundColor' => self::$colors,
                    'borderColor' => self::$colors,
                    'borderWidth' => 1,
                    'hoverOffset'=>6,
                    'barThickness'=> 24,
                ],
            ],
            'labels' => $appointmentLabels,
            'options' => [
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
            'maintainAspectRatio' => false, // Allow custom height
            'height' => 400, // Set chart height in pixels
        ],
        ];
    }


    protected function getType(): string
    {
        return 'bar';
    }
    public static function canView(): bool
    {
        return auth()->user()->role === 'admin';
    }


}
