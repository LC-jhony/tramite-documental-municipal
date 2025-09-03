<?php

namespace Database\Seeders;

use App\Models\Area;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = [
            ['name' => 'Alcaldía', 'code' => 'ALC', 'status' => true],
            ['name' => 'Secretaría Municipal', 'code' => 'SEC', 'status' => true],
            ['name' => 'Administración y Finanzas', 'code' => 'ADM', 'status' => true],
            ['name' => 'Recursos Humanos', 'code' => 'RH', 'status' => true],
            ['name' => 'Obras Públicas', 'code' => 'OBP', 'status' => true],
            ['name' => 'Planeamiento y Desarrollo', 'code' => 'PLA', 'status' => true],
            ['name' => 'Catastro y Avalúos', 'code' => 'CAT', 'status' => true],
            ['name' => 'Seremi (Salud Municipal)', 'code' => 'SER', 'status' => true],
            ['name' => 'Inspección y Fiscalización', 'code' => 'INS', 'status' => true],
            ['name' => 'Desarrollo Social', 'code' => 'DSO', 'status' => true],
            ['name' => 'Medio Ambiente', 'code' => 'MED', 'status' => true],
            ['name' => 'Educación y Cultura', 'code' => 'EDU', 'status' => true],
            ['name' => 'Deportes y Recreación', 'code' => 'DEP', 'status' => true],
            ['name' => 'Participación Ciudadana', 'code' => 'PAR', 'status' => true],
            ['name' => 'Tecnologías de la Información', 'code' => 'TI', 'status' => true],
        ];

        foreach ($areas as $area) {
            Area::firstOrCreate(
                ['code' => $area['code']],
                $area
            );
        }
    }
}
