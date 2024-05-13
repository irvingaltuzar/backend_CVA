<?php

namespace Database\Seeders;

use App\Models\CatUserType;
use Illuminate\Database\Seeder;

class CatUserTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$cat_users_type = [
			[
				'description' => 'Super administrador',
			],
			[
				'description' => 'Supervisor',
			],
			[
				'description' => 'Proveedores',
			],
			[
				'description' => 'Locatarios',
			],
			[
				'description' => 'Personal de eventos',
			],
		];

		foreach ($cat_users_type as $key => $cat_user) {
			CatUserType::create($cat_user);
		}
    }
}
