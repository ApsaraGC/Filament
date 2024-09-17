<?php

namespace App\Filament\Widgets;

use App\Models\Doctor;
use App\Models\Patient;
use Filament\Widgets\ChartWidget;

class DoughnutChart extends ChartWidget
{
   // protected static ?string $heading = 'Chart';
    protected static ?string $heading = 'Gender Distribution (Doctors & Patients)';
    protected static ?int $sort = 2;
    protected int| string | array $columnSpan = 'half';

    protected function getData(): array
    {
        {
            // Get the count of doctors by gender
            $doctorsByGender = Doctor::selectRaw('gender, COUNT(*) as count')
                ->groupBy('gender')
                ->pluck('count', 'gender')
                ->toArray();

            // Get the count of patients by gender
            $patientsByGender = Patient::selectRaw('gender, COUNT(*) as count')
                ->groupBy('gender')
                ->pluck('count', 'gender')
                ->toArray();

            // Combine doctor and patient counts by gender
            $genderDistribution = [
                'male' => ($doctorsByGender['male'] ?? 0) + ($patientsByGender['male'] ?? 0),
                'female' => ($doctorsByGender['female'] ?? 0) + ($patientsByGender['female'] ?? 0),
                'others' => ($doctorsByGender['others'] ?? 0) + ($patientsByGender['others'] ?? 0),
            ];

            return [
                'labels' => ['Male', 'Female', 'Others'],
                'datasets' => [
                    [
                        'label' => 'Gender Distribution',
                        'data' => array_values($genderDistribution),
                        'backgroundColor' => [
                            'rgba(54, 162, 235, 0.6)',  // Blue for Male
                            'rgba(255, 99, 132, 0.6)',  // Red for Female
                            'rgba(153, 102, 255, 0.6)', // Purple for Others
                        ],
                        'borderColor' => [
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(153, 102, 255, 1)',
                        ],
                        'borderWidth' => 1,
                    ],
                ],
                'options' => [
                    'maintainAspectRatio' => false,
                    'height' => 350, // Adjust chart height here
                ],
            ];
        }

    }

    protected function getType(): string
    {
        return 'doughnut';
    }
    public static function canView(): bool
    {
        return auth()->user()->role === 'admin';
    }
}
