<?php

namespace App\Services;

use App\Models\SegUsuario;
use App\Models\User;

class AuthenticationService
{
	function getAuthenticatedUserDefaultEnvironment(SegUsuario $user) {

		$user = SegUsuario::with(['location_role.admin_environment'])->where('usuarioId', $user->usuarioId)->first();

		return $user->location_role->first();
	}
}
