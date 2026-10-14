<?php

namespace App\Filament\Resources\DoctorSchedules\Schemas;

use App\Models\DoctorSchedule;
use App\Rules\NoScheduleOverlap;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DoctorScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Bloque horario')
                    ->schema([
                        Select::make('doctor_id')
                            ->label('Médico')
                            ->relationship('doctor', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),

                        Select::make('day_of_week')
                            ->label('Día de la semana')
                            ->options(DoctorSchedule::DAYS)
                            ->required()
                            ->native(false),

                        TimePicker::make('start_time')
                            ->label('Hora de inicio')
                            ->required()
                            ->seconds(false)
                            ->minutesStep(15),

                        TimePicker::make('end_time')
                            ->label('Hora de fin')
                            ->required()
                            ->seconds(false)
                            ->minutesStep(15)
                            ->after('start_time'),

                        Select::make('slot_duration_minutes')
                            ->label('Duración de cada cita (minutos)')
                            ->options([
                                15 => '15 minutos',
                                20 => '20 minutos',
                                30 => '30 minutos',
                                45 => '45 minutos',
                                60 => '60 minutos',
                            ])
                            ->default(30)
                            ->required()
                            ->helperText('Tiempo asignado a cada paciente.'),

                        Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true)
                            ->helperText('Si está desactivado, este bloque no genera franjas disponibles.'),
                    ])
                    ->columns(2),
            ]);
    }
}
