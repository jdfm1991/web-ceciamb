<?php

namespace App\Filament\Resources\Services\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class ServiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del servicio')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->validationMessages([
                                'unique' => 'Ya existe un servicio con este nombre. Por favor, elige otro.',
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

                        FileUpload::make('image')
                            ->label('Imagen')
                            ->image()
                            ->disk('public')
                            ->directory('services')
                            ->imageEditor()
                            ->imageEditorAspectRatios(['16:9', '4:3', '1:1'])
                            ->maxSize(3072)
                            ->columnSpanFull()
                            ->helperText('Imagen representativa del servicio. Máximo 3 MB. Se recomienda formato horizontal (16:9).'),

                        Textarea::make('description')
                            ->label('Descripción')
                            ->rows(4)
                            ->maxLength(1000)
                            ->columnSpanFull()
                            ->helperText('Descripción del servicio que se mostrará en el portal público.'),
                    ])
                    ->columns(2),

                Section::make('Estado')
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true)
                            ->helperText('Si está desactivado, no se mostrará en el portal público.'),
                    ]),
            ]);
    }
}
