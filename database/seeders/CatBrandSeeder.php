<?php

namespace Database\Seeders;

use App\Models\CatBrand;
use App\Models\CatBrandDet;
use Illuminate\Database\Seeder;

class CatBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$cat_brand = [
			[
				'description' => 'Generico',
			],
			[
				'description' => 'Andares',
			],
		];

		foreach ($cat_brand as $key => $cat_b) {
			$brand = CatBrand::create($cat_b);
		}

		CatBrandDet::create([
			'cat_user_type_id' => 1,
			'cat_brand_id' => 1
		]);

		CatBrandDet::create([
			'cat_user_type_id' => 2,
			'cat_brand_id' => 2
		]);

    }
}
