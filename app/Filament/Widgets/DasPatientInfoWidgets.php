<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class DasPatientInfoWidgets extends BaseWidget
{
    protected static string $view = 'filament.widgets.das-patient-info-widgets';

    public function render(): \Illuminate\Contracts\View\View
    {
        $user = Auth::user();
        $totalAppointments = Appointment::where('patient_id', $user->id)->count();

        return view(static::$view, [
            'totalAppointments' => $totalAppointments,
        ]);
    }
    public static function canView(): bool
    {
        return auth()->user()->role === 'patient';
    }
}

