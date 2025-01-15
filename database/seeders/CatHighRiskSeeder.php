<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CatHighRiskJobs;

class CatHighRiskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $highRiskTasks = [
            'Espacios Confinados',
            'Alturas',
            'Con maquinaria pesada',
            'Energía peligrosa',
            'Sustancias químicas',
            'Corte y soldadura'
        ];

        foreach ($highRiskTasks as $task) {
            CatHighRiskJobs::create([
                'description' => $task,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
