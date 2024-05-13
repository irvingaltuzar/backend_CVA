<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ForgotPasswordController extends Controller
{
    public function checkToken(String $token)
	{
		$updatePassword = DB::table('password_resets')
			->where(['token' => $token])
			->where(['deleted_at' => NULL])
			->first();

		if ( !!!$updatePassword || Carbon::now()->gt(Carbon::parse($updatePassword->expiration_date))) {
			$is_validate = false;
		} else {
			$is_validate = true;
		}

		return response()->json([
			'is_validate' => $is_validate
		]);
	}
}
