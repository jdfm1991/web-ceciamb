<?php

namespace App\Filament\Resources\Appointments\Schemas;

use App\Models\Appointment;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class AppointmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Datos del paciente')
                    ->description('Estos datos fueron proporcionados por el paciente al enviar la solicitud.')
                    ->schema([
                        TextInput::make('patient_name')
                            ->label('Nombre')
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('patient_email')
                            ->label('Correo electrónico')
                            ->email()
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('patient_phone')
                            ->label('Teléfono')
                            ->disabled()
                            ->dehydrated(),
                    ])
                    ->columns(2),

                Section::make('Solicitud')
                    ->schema([
                        Select::make('specialty_id')
                            ->label('Especialidad')
                            ->relationship('specialty', 'name')
                            ->disabled()
                            ->dehydrated(),

                        Select::make('doctor_id')
                            ->label('Médico')
                            ->relationship('doctor', 'name')
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('requested_date')
                            ->label('Fecha solicitada')
                            ->disabled()
                            ->dehydrated(),

                        TextInput::make('requested_time')
                            ->label('Hora solicitada')
                            ->disabled()
                            ->dehydrated(),

                        Select::make('status')
                            ->label('Estado')
                            ->options(Appointment::STATUSES)
                            ->disabled()
                            ->dehydrated()
                            ->columnSpanFull()
                            ->helperText('El estado se cambia mediante las acciones del listado.'),
                    ])
                    ->columns(2),

                Section::make('Mensaje del paciente')
                    ->schema([
                        Textarea::make('message')
                            ->label('Mensaje')
                            ->rows(4)
                            ->maxLength(1000)
                            ->disabled()
                            ->dehydrated()
                            ->columnSpanFull(),
                    ]),

                Section::make('Notas internas')
                    ->description('Solo visible para el personal. El paciente no verá estas notas.')
                    ->schema([
                        Textarea::make('internal_notes')
                            ->label('Notas internas')
                            ->rows(4)
                            ->maxLength(1000)
                            ->columnSpanFull()
                            ->helperText('Añade notas sobre el seguimiento de esta cita.'),
                    ]),
            ]);
    }
}