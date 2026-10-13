<?php

namespace App\Filament\Resources\Specialties\Pages;

use App\Filament\Resources\Specialties\SpecialtyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateSpecialty extends CreateRecord
{
    protected static string $resource = SpecialtyResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label('Crear'),

            $this->getCancelFormAction()
                ->label('Cancelar'),
        ];
    }
}
