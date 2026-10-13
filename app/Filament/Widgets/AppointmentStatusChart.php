<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Widgets\ChartWidget;

class AppointmentStatusChart extends ChartWidget
{
    protected ?string $heading = 'Citas por estado';

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 1;

    protected function getData(): array
    {
        $statuses = Appointment::STATUSES;
        $counts = [];
        $labels = [];
        $colors = [];

        $colorMap = [
            'pendiente' => '#f59e0b',
            'confirmada' => '#10b981',
            'cancelada' => '#ef4444',
            'completada' => '#3b82f6',
            'no_asistio' => '#6b7280',
        ];

        foreach ($statuses as $key => $label) {
            $count = Appointment::where('status', $key)->count();

            if ($count > 0) {
                $counts[] = $count;
                $labels[] = $label;
                $colors[] = $colorMap[$key] ?? '#6b7280';
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Citas',
                    'data' => $counts,
                    'backgroundColor' => $colors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}