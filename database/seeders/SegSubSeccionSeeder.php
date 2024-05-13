<?php

namespace Database\Seeders;

use App\Models\SegSubSeccion;
use Illuminate\Database\Seeder;

class SegSubSeccionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$subsections = [
			[
				'secId' => 1,
				'subsecOrden' => 1,
				'subsecDesc' => 'Usuarios',
				'subsecUrl' => '/admin/users',
				'subsecDenegado' => '/notFound',
				'tablaDatos' => '.',
				'mostrar' => 1,
			],
			[
				'secId' => 1,
				'subsecOrden' => 2,
				'subsecDesc' => 'Roles y permisos',
				'subsecUrl' => '/admin/rol',
				'subsecDenegado' => '/notFound',
				'tablaDatos' => '.',
				'mostrar' => 1,
			],
			[
				'secId' => 2,
				'subsecOrden' => 1,
				'subsecDesc' => 'Home',
				'subsecUrl' => '/home',
				'subsecDenegado' => '/notFound',
				'tablaDatos' => '.',
				'mostrar' => 1,
			],
			[
				'secId' => 2,
				'subsecOrden' => 2,
				'subsecDesc' => 'Avisos',
				'subsecUrl' => '/announcements',
				'subsecDenegado' => '/notFound',
				'tablaDatos' => '.',
				'mostrar' => 1,
			],
			[
				'secId' => 2,
				'subsecOrden' => 3,
				'subsecDesc' => 'Permisos',
				'subsecUrl' => '/permits',
				'subsecDenegado' => '/notFound',
				'tablaDatos' => '.',
				'mostrar' => 1,
			],
			[
				'secId' => 2,
				'subsecOrden' => 4,
				'subsecDesc' => 'Quejas',
				'subsecUrl' => '/complaints',
				'subsecDenegado' => '/notFound',
				'tablaDatos' => '.',
				'mostrar' => 1,
			],
			[
				'secId' => 2,
				'subsecOrden' => 5,
				'subsecDesc' => 'Amonestaciones',
				'subsecUrl' => '/warnings',
				'subsecDenegado' => '/notFound',
				'tablaDatos' => '.',
				'mostrar' => 1,
			],
		];

		foreach ($subsections as $key => $subsection) {
			SegSubSeccion::create($subsection);
		}
    }
}
