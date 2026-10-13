<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información personal')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('email')
                            ->label('Correo electrónico')
                            ->email()
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->validationMessages([
                                'unique' => 'Ya existe un usuario con este correo electrónico.',
                            ]),
                    ])
                    ->columns(2),

                Section::make('Acceso')
                    ->schema([
                        Select::make('role')
                            ->label('Rol')
                            ->options(
                                fn(string $operation): array => $operation === 'create'
                                    ? [
                                        'admin' => 'Administrador',
                                        'recepcionista' => 'Recepcionista',
                                    ]
                                    : [
                                        'admin' => 'Administrador',
                                        'recepcionista' => 'Recepcionista',
                                        'paciente' => 'Paciente',
                                    ]
                            )
                            ->required()
                            ->default('recepcionista')
                            ->native(false)
                            ->disabled(fn(?User $record): bool => $record?->role === 'paciente')
                            ->helperText('Los pacientes se registran desde el portal público. No se pueden crear desde el panel.')
                            ->columnSpanFull(),

                        TextInput::make('password')
                            ->label('Contraseña')
                            ->password()
                            ->revealable()
                            ->required(fn(string $operation): bool => $operation === 'create')
                            ->minLength(8)
                            ->dehydrated(fn($state) => filled($state))
                            ->helperText(
                                fn(string $operation): string => $operation === 'create'
                                    ? 'Mínimo 8 caracteres. Se guardará cifrada.'
                                    : 'Deja en blanco para mantener la contraseña actual.'
                            )
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
