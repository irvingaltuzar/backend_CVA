<?php

namespace Database\Seeders;

use App\Models\CatDistributionListType;
use Illuminate\Database\Seeder;

class CatDistributionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$cat_distribution = [
			[
				'description' => 'Generico1',
				'deleted' => 0,
			],
			[
				'description' => 'Generico2',
				'deleted' => 0,
			],
			[
				'description' => 'Generico3',
				'deleted' => 0,
			],
			[
				'description' => 'Generico4',
				'deleted' => 0,
			],
		];

		foreach ($cat_distribution as $key => $cat_dis) {
			CatDistributionListType::create($cat_dis);
		}
    }
}
