<?php

namespace Database\Seeders;

use App\Models\MailAddress;
use App\Models\SegLogin;
use App\Models\SegSubSeccion;
use App\Models\SegUsuario;
use App\Models\User;
use App\Repositories\UserRepository;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class SupervisorUsersTableSeeder extends Seeder
{

	private $userRepository;

	public function __construct(UserRepository $userRepository)
	{
		$this->userRepository = $userRepository;
	}
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users= [
			[
				'usuario' => 'paula.quesada',
				'pwd' => bcrypt('123345'),
				'nombre' => 'Paula',
				'apepa' => 'Quezada',
				'apema' => '',
				'isadm' => false
			],
			[
				'usuario' => 'fernanda.uribe',
				'pwd' => bcrypt('123345'),
				'nombre' => 'Maria Fernanda',
				'apepa' => 'Uribe',
				'apema' => '',
				'isadm' => false
			],
			[
				'usuario' => 'julissa.delatorre',
				'pwd' => bcrypt('123345'),
				'nombre' => 'Julissa',
				'apepa' => 'De la torre',
				'apema' => '',
				'isadm' => false
			],
			[
				'usuario' => 'diana.garcia',
				'pwd' => bcrypt('123345'),
				'nombre' => 'Diana',
				'apepa' => 'García',
				'apema' => '',
				'isadm' => false
			],
			[
				'usuario' => 'alejandra.torres',
				'pwd' => bcrypt('123345'),
				'nombre' => 'Alejandra',
				'apepa' => 'Torres',
				'apema' => '',
				'isadm' => false
			],
			[
				'usuario' => 'fernando.abrego',
				'pwd' => bcrypt('123345'),
				'nombre' => 'Fernando',
				'apepa' => 'Abrego',
				'apema' => '',
				'isadm' => false
			],
			[
				'usuario' => 'jose.villarreal',
				'pwd' => bcrypt('123345'),
				'nombre' => 'Jesús',
				'apepa' => 'Villarreal',
				'apema' => '',
				'isadm' => false
			],
			[
				'usuario' => 'grisell.palomar',
				'pwd' => bcrypt('123345'),
				'nombre' => 'Grisell',
				'apepa' => 'Palomar',
				'apema' => '',
				'isadm' => false
			],
			[
				'usuario' => 'operacion.mtto',
				'pwd' => bcrypt('123345'),
				'nombre' => 'Operadores',
				'apepa' => 'Mantenimiento',
				'apema' => '',
				'isadm' => false
			],
			[
				'usuario' => 'paulina.gloria',
				'pwd' => bcrypt('123345'),
				'nombre' => 'Paulina',
				'apepa' => 'Gloria',
				'apema' => '',
				'isadm' => false
			],
			[
				'usuario' => 'saul.montes',
				'pwd' => bcrypt('123345'),
				'nombre' => 'Saúl',
				'apepa' => 'Montes',
				'apema' => '',
				'isadm' => false
			],
			[
				'usuario' => 'roberto.castro',
				'pwd' => bcrypt('123345'),
				'nombre' => 'José Roberto',
				'apepa' => 'Castro',
				'apema' => 'Rivas',
				'isadm' => false
			],
			[
				'usuario' => 'cesar.martinez',
				'pwd' => bcrypt('123345'),
				'nombre' => 'Cesar Rogelio',
				'apepa' => 'Martinez',
				'apema' => 'López',
				'isadm' => false
			],
			[
				'usuario' => 'arturo.alatorre',
				'pwd' => bcrypt('123345'),
				'nombre' => 'Arturo',
				'apepa' => 'Alatorre',
				'apema' => 'Blanco',
				'isadm' => false
			],
			[
				'usuario' => 'juancarlos.galvan',
				'pwd' => bcrypt('123345'),
				'nombre' => 'Juan Carlos',
				'apepa' => 'Galván',
				'apema' => '',
				'isadm' => false
			],
			[
				'usuario' => 'monitores',
				'pwd' => bcrypt('123345'),
				'nombre' => 'Monitores',
				'apepa' => '',
				'apema' => '',
				'isadm' => false
			],
			[
				'usuario' => 'antonio.adelc',
				'pwd' => bcrypt('123345'),
				'nombre' => 'Antonio',
				'apepa' => 'Alvarez',
				'apema' => 'del Castillo',
				'isadm' => false
			],
		];

		$mail_address = [
			[
				'email' => 'paula.quesada@andares.com',
			],
			[
				'email' => 'fernanda.uribe@andares.com',
			],
			[
				'email' => 'julissa.delatorre@andares.com',
			],
			[
				'email' => 'diana.garcia@andares.com',
			],
			[
				'email' => 'alejandra.torres@andares.com',
			],
			[
				'email' => 'fernando.abrego@andares.com',
			],
			[
				'email' => 'Jose.villarreal@andares.com',
			],
			[
				'email' => 'grisell.palomar@andares.com',
			],
			[
				'email' => 'operacion.mtto@andares.com',
			],
			[
				'email' => 'paulina.gloria@andares.com',
			],
			[
				'email' => 'saul.montes@andares.com',
			],
			[
				'email' => 'roberto.castro@andares.com',
			],
			[
				'email' => 'cesar.martinez@andares.com',
			],
			[
				'email' => 'arturo.alatorre@andares.com',
			],
			[
				'email' => 'juancarlos.galvan@andares.com',
			],
			[
				'email' => 'monitores@andares.com',
			],
			[
				'email' => 'antonio.adelc@andares.com',
			],
		];

		$positions = [
			'Comercialización y Publicidad',
			'Mercadotecnia',
			'Mercadotecnia',
			'Mercadotecnia',
			'Mercadotecnia',
			'Mantenimiento',
			'Ingenieria',
			'Ingenieria',
			'Mantenimiento',
			'Terraza Andares',
			'TI',
			'TI',
			'TI',
			'TI',
			'Estacionamiento',
			'Seguridad',
			'Seguridad'
		];

		foreach ($users as $key => $u) {
			$user_seg = SegUsuario::create($u);

			$user = User::create([
				'cat_brand_id' => 2,
				'cat_user_type_id' => 2,
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
				return $val->subsecId !== 2;
			});

			foreach ($admin_filter as $menu) {
				SegLogin::create([
					'usuarioId' => $user_seg->usuarioId,
					'subsecId' => $menu->subsecId,
					'loginUsr' =>  $user_seg->usuario,
					'loginCrud' => 'C,R,U,D,I,E,P'
				]);
			}

		}
    }
}
