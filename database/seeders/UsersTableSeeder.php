<?php

namespace Database\Seeders;

use App\Models\SegLogin;
use App\Models\SegSubSeccion;
use App\Models\SegUsuario;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users= [
			[
				'usuario' => 'cmontejo',
				'pwd' => bcrypt('123345'),
				'nombre' => 'Carlos',
				'apepa' => 'Montejo',
				'apema' => 'Vazquez',
				'isadm' => true
			],
			[
				'usuario' => 'ajara',
				'pwd' => bcrypt('123345'),
				'nombre' => 'Arturo',
				'apepa' => 'Jara',
				'apema' => '',
				'isadm' => true
			],
		];

		foreach ($users as $key => $u) {
			$user_seg = SegUsuario::create($u);

			User::create([
				'cat_brand_id' => 1,
				'cat_user_type_id' => 1,
				'SEG_USUARIOS_usuarioId' => $user_seg->usuarioId,
				'birth_date' => Carbon::now(),
				'deleted' => 0
			]);

			$seg_subsec = SegSubSeccion::get();

			foreach ($seg_subsec as $menu) {
				SegLogin::create([
					'usuarioId' => $user_seg->usuarioId,
					'subsecId' => $menu->subsecId,
					'loginUsr' => $user_seg->usuario,
					'loginCrud' => 'C,R,U,D,I,E,P'
				]);
			}
		}
    }
}
