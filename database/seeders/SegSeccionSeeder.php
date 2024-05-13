<?php

namespace Database\Seeders;

use App\Models\SecSeccion;
use App\Models\SecSubSeccion;
use App\Models\SegSeccion;
use App\Models\SegSubSeccion;
use Illuminate\Database\Seeder;

class SegSeccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sections = [
			[
				'secDesc' => 'Administración',
				'secOrden' => 1
			],
			[
				'secDesc' => 'Generales',
				'secOrden' => 2
			],
		];

		foreach ($sections as $key => $section) {
			SegSeccion::create($section);
		}
    }
}
