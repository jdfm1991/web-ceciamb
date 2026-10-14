<?php

namespace App\Filament\Resources\Appointments\Tables;

use App\Mail\AppointmentRescheduledMail;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Specialty;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Services\AppointmentSlotService;
use Carbon\Carbon;


class AppointmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('patient_name')
                    ->label('Paciente')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('patient_phone')
                    ->label('Teléfono')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('doctor.name')
                    ->label('Médico')
                    ->default('Sin asignar')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('specialty.name')
                    ->label('Especialidad')
                    ->default('Sin asignar')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('requested_date')
                    ->label('Fecha')
                    ->date('d/m/Y')
                    ->sortable(),

                TextColumn::make('requested_time')
                    ->label('Hora')
                    ->time('H:i')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => Appointment::STATUSES[$state] ?? $state)
                    ->color(fn(string $state): string => match ($state) {
                        'pendiente' => 'warning',
                        'confirmada' => 'success',
                        'cancelada' => 'danger',
                        'completada' => 'info',
                        'no_asistio' => 'gray',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('expires_at')
                    ->label('Expira')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->color(fn($record) => $record?->isExpired() ? 'danger' : null),

                TextColumn::make('created_at')
                    ->label('Solicitada')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Estado')
                    ->options(Appointment::STATUSES)
                    ->multiple(),

                SelectFilter::make('doctor_id')
                    ->label('Médico')
                    ->relationship('doctor', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('specialty_id')
                    ->label('Especialidad')
                    ->relationship('specialty', 'name')
                    ->searchable()
                    ->preload(),

                Filter::make('requested_date')
                    ->label('Rango de fechas')
                    ->form([
                        DatePicker::make('from')
                            ->label('Desde'),
                        DatePicker::make('until')
                            ->label('Hasta'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn(Builder $query, $date): Builder => $query->whereDate('requested_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn(Builder $query, $date): Builder => $query->whereDate('requested_date', '<=', $date),
                            );
                    }),
            ])
            ->recordActions([
                ViewAction::make(),

                Action::make('confirmar')
                    ->label('Confirmar')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Confirmar cita')
                    ->modalDescription('¿Confirmas esta cita? El paciente recibirá una notificación si el sistema de correos está configurado.')
                    ->visible(fn(Appointment $record): bool => $record->canTransitionTo('confirmada'))
                    ->action(function (Appointment $record): void {
                        $record->update([
                            'status' => 'confirmada',
                            'expires_at' => null,
                        ]);

                        Notification::make()
                            ->title('Cita confirmada')
                            ->success()
                            ->send();
                    }),

                Action::make('cancelar')
                    ->label('Cancelar')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Cancelar cita')
                    ->modalDescription('¿Cancelas esta cita? El paciente deberá enviar una nueva solicitud.')
                    ->visible(fn(Appointment $record): bool => $record->canTransitionTo('cancelada'))
                    ->action(function (Appointment $record): void {
                        $record->update([
                            'status' => 'cancelada',
                            'expires_at' => null,
                        ]);

                        Notification::make()
                            ->title('Cita cancelada')
                            ->warning()
                            ->send();
                    }),

                Action::make('completar')
                    ->label('Completar')
                    ->icon('heroicon-o-check-badge')
                    ->color('info')
                    ->requiresConfirmation()
                    ->modalHeading('Marcar como completada')
                    ->modalDescription('¿Marcas esta cita como completada? El paciente ya fue atendido.')
                    ->visible(fn(Appointment $record): bool => $record->canTransitionTo('completada'))
                    ->action(function (Appointment $record): void {
                        $record->update(['status' => 'completada']);

                        Notification::make()
                            ->title('Cita completada')
                            ->success()
                            ->send();
                    }),

                Action::make('no_asistio')
                    ->label('No asistió')
                    ->icon('heroicon-o-user-minus')
                    ->color('gray')
                    ->requiresConfirmation()
                    ->modalHeading('Marcar como no asistió')
                    ->modalDescription('¿El paciente no asistió a la cita?')
                    ->visible(fn(Appointment $record): bool => $record->canTransitionTo('no_asistio'))
                    ->action(function (Appointment $record): void {
                        $record->update(['status' => 'no_asistio']);

                        Notification::make()
                            ->title('Cita marcada como no asistió')
                            ->warning()
                            ->send();
                    }),

                Action::make('reagendar')
                    ->label('Reagendar')
                    ->icon('heroicon-o-arrow-path')
                    ->color('primary')
                    ->modalHeading('Reagendar cita')
                    ->modalDescription('Crea una nueva solicitud vinculada a esta. La cita original quedará con su estado actual.')
                    ->visible(fn(Appointment $record): bool => in_array($record->status, ['cancelada', 'no_asistio']))
                    ->form([
                        Select::make('specialty_id')
                            ->label('Especialidad')
                            ->options(fn() => Specialty::active()->pluck('name', 'id'))
                            ->searchable()
                            ->default(fn(Appointment $record) => $record->specialty_id),

                        Select::make('doctor_id')
                            ->label('Médico')
                            ->options(fn() => Doctor::active()->pluck('name', 'id'))
                            ->searchable()
                            ->required()
                            ->live()
                            ->default(fn(Appointment $record) => $record->doctor_id)
                            ->helperText('Selecciona un médico para ver las franjas disponibles.'),

                        DatePicker::make('requested_date')
                            ->label('Nueva fecha')
                            ->required()
                            ->minDate(now()->addDay())
                            ->live(),

                        TimePicker::make('requested_time')
                            ->label('Nueva hora')
                            ->required()
                            ->seconds(false)
                            ->minutesStep(15),
                    ])
                    ->action(function (Appointment $record, array $data): void {
                        // Validar que el médico esté activo
                        $doctor = Doctor::active()->find($data['doctor_id']);
                        if (!$doctor) {
                            Notification::make()
                                ->title('Médico no disponible')
                                ->body('El médico seleccionado no está activo. Elige otro.')
                                ->danger()
                                ->send();
                            return;
                        }

                        // Verificar disponibilidad de la franja
                        $slotService = app(\App\Services\AppointmentSlotService::class);
                        $requestedDate = \Carbon\Carbon::createFromFormat('Y-m-d', $data['requested_date']);
                        $availableSlots = $slotService->getAvailableSlots($doctor, $requestedDate);

                        $requestedTime = $data['requested_time'];
                        // Normalizar a HH:MM:SS
                        if (strlen($requestedTime) === 5) {
                            $requestedTime .= ':00';
                        }

                        $slotExists = collect($availableSlots)
                            ->contains('start_time_full', $requestedTime);

                        if (!$slotExists) {
                            Notification::make()
                                ->title('Franja no disponible')
                                ->body("El {$doctor->name} no tiene disponible la hora seleccionada. Por favor, elige otra fecha u hora.")
                                ->danger()
                                ->persistent()
                                ->send();
                            return;
                        }

                        // Crear la nueva cita
                        $newAppointment = Appointment::create([
                            'patient_name' => $record->patient_name,
                            'patient_email' => $record->patient_email,
                            'patient_phone' => $record->patient_phone,
                            'user_id' => $record->user_id,
                            'doctor_id' => $data['doctor_id'],
                            'specialty_id' => $data['specialty_id'],
                            'requested_date' => $data['requested_date'],
                            'requested_time' => $requestedTime,
                            'message' => $record->message,
                            'status' => 'pendiente',
                            'parent_appointment_id' => $record->id,
                            'expires_at' => now()->addHours(24),
                        ]);

                        // Notificar al paciente
                        try {
                            \Illuminate\Support\Facades\Mail::to($newAppointment->patient_email)
                                ->send(new \App\Mail\AppointmentRescheduledMail($newAppointment, $record));
                        } catch (\Throwable $e) {
                            report($e);
                        }

                        Notification::make()
                            ->title('Cita reagendada')
                            ->body("Se creó una nueva solicitud para el {$data['requested_date']} a las {$requestedTime}. El paciente ha sido notificado.")
                            ->success()
                            ->send();
                    }),

                DeleteAction::make()
                    ->visible(fn(Appointment $record): bool => $record->status === 'pendiente'),
            ])
            ->toolbarActions([
                Action::make('export')
                    ->label('Exportar a CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('success')
                    ->action(function ($livewire): StreamedResponse {
                        return self::exportToCsv($livewire);
                    }),
            ])
            ->defaultSort('requested_date', 'asc');
    }

    /**
     * Exporta las citas filtradas a CSV.
     */
    protected static function exportToCsv($livewire): StreamedResponse
    {
        // Obtener la query con los filtros aplicados
        $query = $livewire->getFilteredTableQuery();

        // Cargar relaciones para evitar N+1
        $appointments = $query
            ->with(['doctor:id,name', 'specialty:id,name'])
            ->orderBy('requested_date', 'desc')
            ->orderBy('requested_time', 'desc')
            ->get();

        $filename = 'citas_' . now()->format('Y-m-d_His') . '.csv';

        return response()->streamDownload(function () use ($appointments) {
            $handle = fopen('php://output', 'w');

            // BOM para UTF-8 (Excel lo reconoce)
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // Cabeceras
            fputcsv($handle, [
                'Referencia',
                'Paciente',
                'Email',
                'Teléfono',
                'Médico',
                'Especialidad',
                'Fecha',
                'Hora',
                'Estado',
                'Solicitada el',
                'Notas internas',
            ]);

            // Filas
            foreach ($appointments as $appointment) {
                fputcsv($handle, [
                    'CITA-' . str_pad($appointment->id, 6, '0', STR_PAD_LEFT),
                    $appointment->patient_name,
                    $appointment->patient_email,
                    $appointment->patient_phone,
                    $appointment->doctor?->name ?? 'Sin asignar',
                    $appointment->specialty?->name ?? 'Sin asignar',
                    $appointment->requested_date?->format('d/m/Y') ?? '',
                    $appointment->requested_time ? substr($appointment->requested_time, 0, 5) : '',
                    \App\Models\Appointment::STATUSES[$appointment->status] ?? $appointment->status,
                    $appointment->created_at?->format('d/m/Y H:i') ?? '',
                    $appointment->internal_notes ?? '',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
