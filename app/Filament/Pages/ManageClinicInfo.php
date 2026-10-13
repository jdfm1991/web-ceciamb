<?php

namespace App\Filament\Pages;

use App\Models\ClinicInfo;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class ManageClinicInfo extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    protected static ?string $navigationLabel = 'Información institucional';

    protected static ?string $title = 'Información institucional';

    protected static ?int $navigationSort = 7;

    protected string $view = 'filament.pages.manage-clinic-info';

    public ?array $data = [];

    /**
     * Campos requeridos.
     */
    public const REQUIRED_KEYS = ['name', 'rif', 'address', 'phone', 'email'];

    /**
     * Todos los campos gestionables.
     */
    public const ALL_KEYS = [
        'name',
        'rif',
        'slogan',
        'address',
        'phone',
        'email',
        'instagram',
        'facebook',
        'whatsapp',
        'logo',
    ];

    public function mount(): void
    {
        $this->form->fill(ClinicInfo::allAsArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identidad institucional')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre legal')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('rif')
                            ->label('RIF')
                            ->required()
                            ->maxLength(20)
                            ->placeholder('J-12345678-9')
                            ->helperText('Formato: J-XXXXXXXX-X')
                            ->rules(['regex:/^[JVEGP]-?\d{8}-?\d$/i'])
                            ->validationMessages([
                                'regex' => 'El RIF debe tener el formato J-XXXXXXXX-X (letra, 8 dígitos, guion, 1 dígito).',
                            ]),

                        TextInput::make('slogan')
                            ->label('Slogan')
                            ->maxLength(255)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('Contacto')
                    ->schema([
                        Textarea::make('address')
                            ->label('Dirección')
                            ->required()
                            ->rows(3)
                            ->maxLength(500)
                            ->columnSpanFull(),

                        TextInput::make('phone')
                            ->label('Teléfono')
                            ->required()
                            ->tel()
                            ->maxLength(30),

                        TextInput::make('email')
                            ->label('Correo electrónico')
                            ->required()
                            ->email()
                            ->maxLength(150),
                    ])
                    ->columns(2),

                Section::make('Redes sociales')
                    ->description('Opcional. Se mostrarán en el pie de página del portal público.')
                    ->schema([
                        TextInput::make('instagram')
                            ->label('Instagram')
                            ->prefix('@')
                            ->maxLength(100),

                        TextInput::make('facebook')
                            ->label('Facebook')
                            ->prefix('@')
                            ->maxLength(100),

                        TextInput::make('whatsapp')
                            ->label('WhatsApp')
                            ->tel()
                            ->maxLength(30)
                            ->helperText('Solo números, con código de país. Ejemplo: 584121278092.'),
                    ])
                    ->columns(3),

                Section::make('Logo institucional')
                    ->schema([
                        FileUpload::make('logo')
                            ->label('Logo')
                            ->image()
                            ->disk('public')
                            ->directory('clinic')
                            ->imageEditor()
                            ->maxSize(2048)
                            ->helperText('Se recomienda formato PNG con fondo transparente. Máximo 2 MB.'),
                    ]),
            ])
            ->statePath('data');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('save')
                ->label('Guardar cambios')
                ->submit('save'),

            Action::make('reset')
                ->label('Restaurar')
                ->color('gray')
                ->action(function () {
                    $this->mount();
                }),
        ];
    }

    public function save(): void
    {
        $data = $this->form->getState();

        foreach (self::ALL_KEYS as $key) {
            $value = $data[$key] ?? null;

            // Convertir el logo a ruta relativa si viene como array (FileUpload)
            if ($key === 'logo' && is_array($value)) {
                $value = reset($value);
            }

            ClinicInfo::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        Notification::make()
            ->title('Información actualizada')
            ->body('Los cambios se reflejarán en el portal público inmediatamente.')
            ->success()
            ->send();

        $this->form->fill(ClinicInfo::allAsArray());
    }
}
