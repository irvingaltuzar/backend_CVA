<?php

namespace Database\Seeders;

use App\Models\MailAddress;
use App\Models\SegLogin;
use App\Models\SegSubSeccion;
use App\Models\SegUsuario;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class LesseeUsersAdministrationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$users = [
			['usuario' => 'Marisol.Vazquez','pwd' => bcrypt('123345'),'nombre' => 'Marisol','apepa' => 'Vazquez','apema' => '','isadm' => false],
			['usuario' => 'Josselyn.avalos;','pwd' => bcrypt('123345'),'nombre' => 'Josselyn','apepa' => 'avalos;','apema' => '','isadm' => false],
			['usuario' => 'Maria.del','pwd' => bcrypt('123345'),'nombre' => 'Maria','apepa' => 'del','apema' => '','isadm' => false],
			['usuario' => 'Stephanie.Ceballos','pwd' => bcrypt('123345'),'nombre' => 'Stephanie','apepa' => 'Ceballos','apema' => '','isadm' => false],
			['usuario' => 'FABIOLA.ZAPATA','pwd' => bcrypt('123345'),'nombre' => 'FABIOLA','apepa' => 'ZAPATA','apema' => '','isadm' => false],
			['usuario' => 'NI.NI','pwd' => bcrypt('123345'),'nombre' => 'NI','apepa' => 'NI','apema' => '','isadm' => false],
		];


		$mail_address = [
			['email' => 'marisolvazquezgtz@gmail.com',],
			['email' => 'joss.mavalos@outlook.com',],
			['email' => 'mariaplasencia@inoplay.com',],
			['email' => 'sceballosb@televisa.com.mx',],
			['email' => 'gdl@enigmarooms.com.mx; arena.angeles@gmail.com',],
			['email' => '',],
		];


		$positions = [
			'Administracion',
			'Administracion',
			'Administracion',
			'Administracion',
			'Administracion',
			'Administracion',
		];

		$brands = [
			173,
			174,
			175,
			176,
			177,
			178,
		];

		foreach ($users as $key => $u) {
			$user_seg = SegUsuario::create($u);

			$user = User::create([
				'cat_brand_id' => $brands[$key],
				'cat_user_type_id' => 4,
				'SEG_USUARIOS_usuarioId' => $user_seg->usuarioId,
				'birth_date' => Carbon::now(),
				'position' => $positions[$key],
				'deleted' => 0
			]);

			MailAddress::create([
				'users_id' => $user->id,
				'mail' => $mail_address[$key]['email']
			]);

			$seg_subsec = SegSubSeccion::get();

			$admin_filter = $seg_subsec->filter( function($val) {
				return $val->subsecId >= 3;
			});

			foreach ($admin_filter as $menu) {
				SegLogin::create([
					'usuarioId' => $user_seg->usuarioId,
					'subsecId' => $menu->subsecId,
					'loginUsr' =>  $user_seg->usuario,
					'loginCrud' => 'C,R,I,E,P'
				]);
			}

		}
    }
}
