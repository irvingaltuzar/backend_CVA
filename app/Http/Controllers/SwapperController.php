<?php

namespace App\Http\Controllers;

use App\Models\SegUsuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SwapperController extends Controller
{
    public function environment(Request $request)
	{
		$user = Auth::user();

		$user = SegUsuario::with(['location_role' => function ($q) use ($request) {
					return $q->with('admin_environment')
							->whereHas('admin_environment', function ($query) use ($request) {
								return $query->where('id', $request->environment);
							});
				}])
				->where('usuarioId', $user->usuarioId)->first();
		$environment = $user->location_role->first();

		return response()->json($environment);
	}
}
