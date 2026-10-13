<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Service;
use App\Models\Specialty;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class ClinicStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $activeDoctors = Doctor::active()->count();
        $totalDoctors = Doctor::count();

        $activeSpecialties = Specialty::active()->count();
        $totalSpecialties = Specialty::count();

        $pendingAppointments = Appointment::where('status', 'pendiente')->count();
        $todayAppointments = Appointment::whereDate('requested_date', today())->count();

        $activeServices = Service::active()->count();
        $totalServices = Service::count();

        return [
            Stat::make('Médicos activos', $activeDoctors)
                ->description("de {$totalDoctors} registrados")
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary')
                ->chart($this->getDoctorChartData()),

            Stat::make('Especialidades activas', $activeSpecialties)
                ->description("de {$totalSpecialties} registradas")
                ->descriptionIcon('heroicon-m-rectangle-stack')
                ->color('info'),

            Stat::make('Citas pendientes', $pendingAppointments)
                ->description($pendingAppointments > 0 ? 'Requieren atención' : 'Todo al día')
                ->descriptionIcon($pendingAppointments > 0 ? 'heroicon-m-exclamation-triangle' : 'heroicon-m-check-circle')
                ->color($pendingAppointments > 0 ? 'warning' : 'success'),

            Stat::make('Citas para hoy', $todayAppointments)
                ->description('Programadas para hoy')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary'),

            Stat::make('Servicios activos', $activeServices)
                ->description("de {$totalServices} registrados")
                ->descriptionIcon('heroicon-m-briefcase')
                ->color('success'),
        ];
    }

    /**
     * Datos de ejemplo para el mini-gráfico de médicos.
     * Se puede reemplazar con datos reales (por ejemplo, médicos creados por mes).
     */
    protected function getDoctorChartData(): array
    {
        return [2, 3, 5, 4, 6, 7, 8];
    }
}
