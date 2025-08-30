<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TypeDocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'Solicitud de Información',
                'code' => 'SI',
                'status' => true,
                'response_time_days' => 5
            ],
            [
                'name' => 'Queja Formal',
                'code' => 'QF',
                'status' => true,
                'response_time_days' => 3
            ],
            [
                'name' => 'Reclamo',
                'code' => 'RC',
                'status' => true,
                'response_time_days' => 7
            ],
            [
                'name' => 'Petición de Acceso a Documentos',
                'code' => 'PAD',
                'status' => true,
                'response_time_days' => 10
            ],
            [
                'name' => 'Solicitud de Certificado',
                'code' => 'SC',
                'status' => true,
                'response_time_days' => 2
            ],
            [
                'name' => 'Denuncia',
                'code' => 'DN',
                'status' => true,
                'response_time_days' => 15
            ],
        ];
        foreach ($types as $type) {
            DB::table('type_documents')->updateOrInsert(
                ['code' => $type['code']], // Condición de unicidad
                [
                    'name' => $type['name'],
                    'code' => $type['code'],
                    'status' => $type['status'],
                    'response_time_days' => $type['response_time_days'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
