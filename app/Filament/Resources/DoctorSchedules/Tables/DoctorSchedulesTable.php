<?php

namespace App\Filament\Resources\DoctorSchedules\Tables;

use App\Models\DoctorSchedule;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class DoctorSchedulesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('doctor.name')
                    ->label('Médico')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('day_of_week')
                    ->label('Día')
                    ->formatStateUsing(fn (int $state): string => DoctorSchedule::DAYS[$state] ?? 'Desconocido')
                    ->badge()
                    ->color('primary')
                    ->sortable(),

                TextColumn::make('start_time')
                    ->label('Inicio')
                    ->time('H:i')
                    ->sortable(),

                TextColumn::make('end_time')
                    ->label('Fin')
                    ->time('H:i')
                    ->sortable(),

                TextColumn::make('slot_duration_minutes')
                    ->label('Duración')
                    ->formatStateUsing(fn (int $state): string => "{$state} min")
                    ->badge()
                    ->color('info')
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('doctor_id')
                    ->label('Médico')
                    ->relationship('doctor', 'name')
                    ->searchable()
                    ->preload(),

                SelectFilter::make('day_of_week')
                    ->label('Día')
                    ->options(DoctorSchedule::DAYS),

                TernaryFilter::make('is_active')
                    ->label('Activo')
                    ->placeholder('Todos')
                    ->trueLabel('Solo activos')
                    ->falseLabel('Solo inactivos'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('doctor_id', 'asc')
            ->defaultSort('day_of_week', 'asc');
    }
}