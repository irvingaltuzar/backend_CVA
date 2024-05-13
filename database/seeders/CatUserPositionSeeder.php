<?php

namespace Database\Seeders;

use App\Models\CatUserPosition;
use Illuminate\Database\Seeder;

class CatUserPositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$cat_position = [
			[
				'description' => 'Comercialización y Publicidad',
			],
			[
				'description' => 'Mercadotecnia',
			],
			[
				'description' => 'Mantenimiento',
			],
			[
				'description' => 'Ingenieria',
			],
			[
				'description' => 'Terraza Andares',
			],
			[
				'description' => 'TI',
			],
			[
				'description' => 'Estacionamiento',
			],
			[
				'description' => 'Seguridad',
			],
			[
				'description' => 'Operación',
			],
			[
				'description' => 'Administración',
			],
			[
				'description' => 'Proveedor',
			],
		];

		foreach ($cat_position as $key => $cat_p) {
			$position = CatUserPosition::create($cat_p);
		}
    }
}
