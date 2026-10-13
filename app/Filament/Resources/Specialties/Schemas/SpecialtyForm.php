<?php

namespace App\Filament\Resources\Specialties\Schemas;

use App\Models\Specialty;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class SpecialtyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nombre')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->validationMessages([
                        'unique' => 'Ya existe una especialidad con este nombre. Por favor, elige otro.',
                    ])
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, $state, callable $set) {
                        if ($operation === 'create') {
                            $set('slug', Str::slug($state));
                        }
                    }),

                TextInput::make('slug')
                    ->label('Slug (URL)')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true)
                    ->helperText('Identificador único en la URL. Se genera automáticamente desde el nombre.'),

                Select::make('category')
                    ->label('Categoría')
                    ->options(Specialty::CATEGORIES)
                    ->required()
                    ->default('medica'),

                Textarea::make('description')
                    ->label('Descripción')
                    ->rows(3)
                    ->maxLength(1000)
                    ->columnSpanFull()
                    ->helperText('Descripción opcional que se mostrará en el portal público.'),

                Toggle::make('is_active')
                    ->label('Activa')
                    ->default(true)
                    ->helperText('Si está desactivada, no se mostrará en el portal público.'),
            ]);
    }
}
