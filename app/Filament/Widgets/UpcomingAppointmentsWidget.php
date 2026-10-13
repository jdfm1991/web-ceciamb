<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class UpcomingAppointmentsWidget extends TableWidget
{
    protected static ?string $heading = 'Próximas citas';

    protected static ?int $sort = 3;

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Appointment::query()
                    ->whereIn('status', ['pendiente', 'confirmada'])
                    ->where('requested_date', '>=', today())
                    ->orderBy('requested_date')
                    ->orderBy('requested_time')
                    ->limit(5)
            )
            ->columns([
                TextColumn::make('patient_name')
                    ->label('Paciente')
                    ->weight('bold'),

                TextColumn::make('doctor.name')
                    ->label('Médico')
                    ->default('Sin asignar'),

                TextColumn::make('specialty.name')
                    ->label('Especialidad')
                    ->default('Sin asignar')
                    ->toggleable(),

                TextColumn::make('requested_date')
                    ->label('Fecha')
                    ->date('d/m/Y'),

                TextColumn::make('requested_time')
                    ->label('Hora')
                    ->time('H:i'),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => Appointment::STATUSES[$state] ?? $state)
                    ->color(fn(string $state): string => match ($state) {
                        'pendiente' => 'warning',
                        'confirmada' => 'success',
                        default => 'gray',
                    }),
            ])
            ->paginated(false);
    }

    public static function canView(): bool
    {
        return in_array(auth()->user()?->role, ['admin', 'recepcionista'], true);
    }
}
