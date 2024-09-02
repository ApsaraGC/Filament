<?php

namespace App\Filament\Widgets;

use App\Models\Doctor;
use App\Models\Appointment;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class DasDoctorInfoWidgets extends BaseWidget
{
        protected static string $view = 'filament.widgets.das-doctor-info-widgets';

        protected static string $routePath = 'doctor';

        protected function getStats(): array
        {
            $doctorId = auth()->user()->doctor->id ?? '';

            $today = Carbon::today();

            $todayAppointments = Appointment::where('doctor_id', $doctorId)
                ->whereDate('date_time', $today)
                ->count();


            return [
                Stat::make('Appointments Today', $todayAppointments)
                    ->description('Appointments scheduled for today')
                    ->color('success')
                    ->chart([0, $todayAppointments]),


            ];
        }

        public static function canView(): bool
        {
            return auth()->user()->role === 'doctor';
        }
    }
