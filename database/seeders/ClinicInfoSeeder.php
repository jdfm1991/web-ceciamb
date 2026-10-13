<?php

namespace Database\Seeders;

use App\Models\ClinicInfo;
use Illuminate\Database\Seeder;

class ClinicInfoSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            'name' => 'Hospital de Clínicas de Ceciamb, C.A',
            'rif' => 'J-30553771-2',
            'slogan' => 'Salud total y accesible con sensibilidad humana',
            'address' => 'Detrás del C. C. Río Caura, C. Yuruani, Ciudad Guayana 8050, Bolívar',
            'phone' => '0286-7120100',
            'email' => 'atencionalpublico@grupoceciamb.com',
            'instagram' => '@clinicaceciamb',
            'facebook' => '@clinicaceciamb',
            'whatsapp' => '584121278092',
            'logo' => 'images/logo.png',
        ];

        foreach ($data as $key => $value) {
            ClinicInfo::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}