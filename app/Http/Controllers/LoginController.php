<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\UserNameRequest;
use App\Models\SegUsuario;
use App\Services\AuditService;
use App\Services\AuthenticationService;
use App\Services\SendEmailService;
use Carbon\Carbon;
use Error;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class LoginController extends Controller
{
	private $auditService, $sendEmail, $authService;

	public function __construct(AuditService $auditService, SendEmailService $sendEmail, AuthenticationService $authService)
	{
			$this->auditService = $auditService;
			$this->sendEmail = $sendEmail;
			$this->authService = $authService;
	}

	public function login(Request $request) : JsonResponse
	{
		// return response()->json($this->auditService->store(['a']));
		try {

			$credentials = ['usuario' => $request->user, 'password' => $request->password];

			if (Auth::attempt($credentials)) {

				$user = Auth::user();

				$environment = $this->authService->getAuthenticatedUserDefaultEnvironment($user);

				$_SESSION['environment'] = $environment;

				$this->auditService->store([
						'event' => 'LoginController@login',
						'subsecid' => 3,
						'error' => false,
						'error_code' => 0,
						'msg' => 'Se inició sesión'
					]);

				return response()->json([
					'logged' => true
				]);
			}

			$this->auditService->store([
				'event' => 'LoginController@login',
				'subsecid' => 3,
				'error' => true,
				'error_msg' => 'Error de autenticación'
			]);

			return response()->json([
				'message' => _('auth.failed')
			], 401);
	   } catch (\Throwable $e) {
        // Manejar errores inesperados
        return response()->json([
            'message' => 'Ocurrió un error inesperado.',
            'error' => $e->getMessage()
        ], 500);
    }
	}

	public function checkAccount(Request $request)
	{
		$user = SegUsuario::where('usuario', $request->user)->where("borrado",0)->first();

		if (!!!$user) {
			return response()->json([
				'isBlocked' => false
			], 200);
		} else {
			if (!!$user->bloqueado) {
				return response()->json([
					'message' => _('Tu cuenta se encuentra bloqueada, contacta al administrador.'),
					'isBlocked' => true
				], 401);
			} else {
				return response()->json([
					'isBlocked' => false
				], 200);
			}
		}

	}

	public function checkUser(UserNameRequest $request)
	{
		$user = SegUsuario::where('usuario', $request->username)->first();

		if (!!$user) {
			return response()->json([
				'exists' => true,
				'user' => $user
			], 200);
		} else {
			return response()->json([
				'exists' => false,
				'user' => $user
			], 200);
		}
	}

	public function changePwd(Request $request)
	{
		$user_sec = SegUsuario::with(['user.mails'])->where('usuario', $request->usuario)->first();

		$token = Uuid::uuid4();

		DB::table('password_resets')->insert(
			[
				'username' => $request->usuario,
				'token' => $token,
				'created_at' => Carbon::now(),
				'expiration_date' =>Carbon::now()->addMinutes(10)
			]
		);

		$this->sendEmail->sendTokenPassword($user_sec->user[0]->mails->pluck('mail'), $token);

		return true;
	}

	public function changePassword(Request $request)
	{
		$updatePassword = DB::table('password_resets')
		->where(['token' => $request->_user_token])
		->where(['deleted_at' => NULL])
		->first();

		DB::table('password_resets')
		->where(['token' => $request->_user_token])
		->where(['deleted_at' => NULL])
		->update([
			'deleted_at' => Carbon::now()
		]);

		$user = SegUsuario::where('usuario', $updatePassword->username)->update([
			'pwd' => bcrypt($request->new_password)
		]);

		return $user;
	}

	public function logout(Request $request)
	{
		Auth::logout();

		$request->session()->invalidate();

		$request->session()->regenerateToken();

		return response()->json([
			'logged_out' => true
		]);
	}
}
