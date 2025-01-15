<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CatDocumentsWorkPermit;

class CatDocumentsWorkPermitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $documents = [
            'comprobante de vigencia de derechos del imss y/o comprobante pago sua',
            'formato de análisis de tarea segura',
            'constancia de capacitación',
            'plan de trabajo a realizar',
            'Otros',
            'Imagen',
            'Catalogo Alto Riesgo'
        ];

        foreach ($documents as $document) {
            CatDocumentsWorkPermit::create([
                'description' => $document,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
