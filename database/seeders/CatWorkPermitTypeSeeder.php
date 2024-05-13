<?php

namespace Database\Seeders;

use App\Models\CatWorkPermitType;
use Illuminate\Database\Seeder;

class CatWorkPermitTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cat_work_permit = [
			[
				'description' => 'Comercialización y Publicidad'
			],
			[
				'description' => 'Mercadotecnia'
			],
			[
				'description' => 'Mantenimiento'
			],
			[
				'description' => 'Ingenieria'
			],
			[
				'description' => 'Terraza Andares'
			],
			[
				'description' => 'TI'
			],
			[
				'description' => 'Estacionamiento'
			],
			[
				'description' => 'Seguridad'
			],
		];

		foreach ($cat_work_permit as $cat_w) {
			CatWorkPermitType::create($cat_w);
		}
    }
}
