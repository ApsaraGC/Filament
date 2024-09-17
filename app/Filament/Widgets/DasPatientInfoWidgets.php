<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Illuminate\Support\Carbon;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DasPatientInfoWidgets extends BaseWidget
{
    protected static string $view = 'filament.widgets.das-patient-info-widgets';
    protected static string $routePath ='patient';

    public function getStats(): array
    {

        $user = auth()->user();

        // Ensure user is authenticated and has a related patient record
        if (!$user || !$user->patient) {
            return []; // Handle case where patient record is not available
        }

        // Retrieve the patient's ID for the logged-in user
        $patientId = $user->patient->id;
        $today = Carbon::today();

        $todayAppointments = Appointment::where('patient_id', $patientId)
            ->whereDate('date_time', $today)
            ->count();

            return [
                Stat::make('Your Appointments Today', $todayAppointments)
                    ->description('Total Appointments')
                    ->color('success')
                    ->chart([0, $todayAppointments]),


            ];
    }

    public static function canView(): bool
    {
        return auth()->user()->role === 'patient';
    }
}
