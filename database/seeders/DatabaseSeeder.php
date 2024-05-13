<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
		$this->call([
			CatWorkPermitTypeSeeder::class,
			CatUserTypeSeeder::class,
			CatBrandSeeder::class,
			SegSeccionSeeder::class,
			SegSubSeccionSeeder::class,
			UsersTableSeeder::class,
			SupervisorUsersTableSeeder::class,
			LesseeBrandSeeder::class,
			LesseeUsersTableSeeder::class,
			LesseeUsersMarketingTableSeeder::class,
			LesseeUsersAdministrationTableSeeder::class,
			CatDistributionTypeSeeder::class
        ]);
    }
}
