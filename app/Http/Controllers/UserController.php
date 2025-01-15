<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePermissionRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Auth;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\CatUserType;
use App\Models\MailAddress;
use App\Models\SegLogin;
use App\Models\SegSeccion;
use App\Models\SegSubSeccion;
use App\Models\SegUsuario;
use App\Models\BucketUsersBrands;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\WorkPermitBoss;
use App\Models\Environment;
use App\Services\AuditService;
use App\Services\SendEmailService;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

	private $userRepository, $auditService, $sendEmail;

	public function __construct(UserRepository $userRepository, AuditService $auditService,SendEmailService $sendEmail)
	{
		$this->userRepository = $userRepository;
		$this->auditService = $auditService;
		$this->sendEmail = $sendEmail;
	}

	public function getUser()
	{
		if(Auth::check()){
			return response()->json([
				'user' => Auth::user()
			]);

		}else{
			return response()->json(null);
		}
	}

	public function getUserComplaint(Request $request)
	{
		$users = User::with('brand')
				->where('show_complaints', true)
				->get()
				->sortBy('brand.description')
				->values()
				->all();

		return response()->json($users);
	}

	function checkEnvironment()
	{
		return response()->json($this->userRepository->getCurrentEnvironment());
	}

	// public function dt(Request $request) : JsonResponse
	// {
	// 	$brands = $this->userRepository->getBrandsByEnvironment($request->current_environment);

	// 	$users = SegUsuario::with(['user' => function ($q) {
	// 					return $q->with(['brand', 'mails' , 'phones' , 'permitBoss' => function ($query) {
	// 						return $query->with(['permitType'])->where('deleted', false);
	// 					}]);
	// 				}])
	// 				->whereHas('user', function ($q) use ($brands){
	// 					return $q->whereIn('cat_brand_id', $brands);
	// 				})
	// 				->where(
	// 					function ($q) use ($request){
	// 						return $q->where('borrado', 0)
	// 						->where('usuarioId', '>', 2)
	// 						->whereRaw("concat(nombre, ' ', apepa) like '%" .$request->search. "%' ")
	// 						->orWhere(
	// 							function ($q) use ($request){
	// 								return $q->where('borrado', 0)
	// 										->where('usuarioId', '>', 2)
	// 										->WhereRelation('user.allbrands',function($q) use ($request){
	// 											return $q->orWhere('description', 'like', "%$request->search%");
	// 								});
	// 							}
	// 						);
	// 					}
	// 				)
	// 				->where(
	// 					function ($q) use ($request){
	// 						return $q->where('borrado', 0)
	// 						->where('usuarioId', '>', 2);
	// 					}
	// 				)
	// 				->paginate(10);

	// 	return response()->json($users);
	// }
	public function dt(Request $request) : JsonResponse
	{
		// // Obtener las marcas relacionadas a través de bucket_users_brand
		// $brands = $this->userRepository->getBrandsByEnvironment($request->current_environment);

		$currentEnvironmentId = $request->current_environment;
		// Obtener los usuarios con las relaciones necesarias
			$users = SegUsuario::with([
				'location_role.admin_environment',
				'user' => function ($q) {
					$q->with([
						'brand',  // Relación a la tabla CatBrand
						'mails',
						'phones',
						'permitBoss' => function ($query) {
							$query->with(['permitType'])->where('deleted', false);
						},
						'allbrands.brand'  // Relación para obtener las marcas relacionadas
					]);
				}
			])
			->where('borrado', 0) // Filtro de borrado directamente aquí
			->whereHas('location_role.admin_environment', function ($q) use ($currentEnvironmentId) {
				// Filtrar por el ID del entorno actual
				$q->where('environment_id', $currentEnvironmentId);
			})
			->where(function ($q) use ($request) {
				$q->whereRaw("concat(nombre, ' ', apepa) like '%" . $request->search . "%'")
				->orWhereHas('user.allbrands.brand', function ($q) use ($request) {
					$q->where('description', 'like', "%{$request->search}%");
				});
			})
			->paginate(10);

		return response()->json($users);
	}
	
	
	public function getUserType() : JsonResponse
	{
		$user_type_id= $this->userRepository->getUser()->user[0]->cat_user_type_id;

		if(	$user_type_id !==1){
			$cat_user = CatUserType::where('description', 'not like', '%Super%')
			->whereNotIn('description', ['personal de eventos', 'seguridad'])
			->get();

		}else{
			$cat_user=CatUserType::where('deleted', 0)
			->get();
		}

		return response()->json($cat_user);
	}

	public function getUserTypeBrand() : JsonResponse
	{
		$cat_user=CatUserType::whereIn('description', ['Locatarios', 'Proveedores'])
		->where('deleted', 0)
		->get();

		return response()->json($cat_user);
	}
	
	public function store(StoreUserRequest $request)
	{

		$pwd = Str::random(8);

		$user_sec = SegUsuario::create([
						'nombre' => $request['nombre'],
						'apepa' => $request['apepa'],
						'apema' => $request['apema'],
						'usuario' => $request['usuario'],
						'pwd' => bcrypt($pwd),
					]);
	
		$this->userRepository->store($user_sec->usuarioId, $request, $pwd);
		$user_sec->load(['user' => function ($q) {
			return $q->with(['brand', 'permitBoss' => function ($query) {
				return $query->with(['permitType'])->where('deleted', false);
			}]);
		}]);

		return response()->json($user_sec);
	}

	public function update(UpdateUserRequest $request)
	{
			// Obtén el valor actual de 'usuario' desde la base de datos
			$currentUser = SegUsuario::where('usuarioId', $request['usuarioId'])->first();
			$currentUsuario = $currentUser->usuario;

			// Actualiza los datos del usuario
			$user_sec = $currentUser->update([
				'nombre' => $request['nombre'],
				'apepa' => $request['apepa'],
				'apema' => $request['apema'],
				'usuario' => $request['usuario'],
			]);

		// Verifica si 'usuario' ha cambiado
			if ($currentUsuario !== $request['usuario']) {
				// Si el campo 'usuario' ha cambiado y hay emails, genera $pwd y envía el email
				if (sizeof($request->emails) > 0) {
					$pwd = Str::random(8); // Genera la contraseña solo si va a enviar el email
					$this->sendEmail->newUser($request->emails, $request, $pwd);

					// Opcional: puedes actualizar la contraseña en la base de datos aquí si es necesario
					$currentUser->update(['pwd' => bcrypt($pwd)]);
				}
			}
		$this->userRepository->update($request['usuarioId'], $request);

		return response()->json($user_sec);
	}

	public function getUserByWarning(int $id)
	{
		$user = User::with('userSec')->where('cat_brand_id', $id)->where('show_warnings', true)->get();

		if (!!$user) {
			return response()->json([
				'response_user' => true,
				'user' => $user
			]);
		} else {
			return response()->json([
				'response_user' => false,
				'user' => ''
			]);
		}
	}

	public function getUserByBrand(int $id)
	{
		// $user = User::with('userSec')->where('cat_brand_id', $id)->get();
		$user = BucketUsersBrands::with('user')->where('cat_brand_id', $id)->get();

		if (!!$user) {
			return response()->json([
				'response_user' => true,
				'user' => $user
			]);
		} else {
			return response()->json([
				'response_user' => false,
				'user' => ''
			]);
		}
	}

	public function getSidebar()
	{
		$sidebar = SegSeccion::with('subsection')
		->get();

		return response()->json($sidebar);
	}

	public function getSubSec()
	{
		$subsec = SegSubSeccion::get();

		return response()->json($subsec);
	}

	public function storePermission(StorePermissionRequest $request)
	{
		$crud = implode(",",$request['permissions']);

		$permission = SegLogin::create([
			'usuarioId' => $request['user']['usuarioId'],
			'subsecId' => $request['sub_section_id'],
			'loginUsr' => $request['user']['usuario'],
			'loginCrud' => $crud
		]);

		return response()->json($permission);
	}

	public function getPermissions()
	{
		$permission = SegUsuario::with(['user' => function($q) {
							$q->with(['brand', 'type']);
						}, 'permissions.link'])
						->where('borrado', 0)
						->where('usuarioId', Auth::user()->usuarioId)
						->first();

		return $permission;
	}

	public function getAdminUsers()
	{
		$admin_users = User::with('userSec')->where('cat_user_type_id', 2)->get();

		return response()->json($admin_users);
	}

	public function getEnvironments()
	{
		$environments = Environment::where('deleted',0)->get();

		return response()->json($environments);
	}

	public function checkNickname(String $nickname)
	{
		$founded = SegUsuario::where('usuario', $nickname)->first();

		if ($founded) {
			return response()->json(true);
		} else {
			return response()->json(false);
		}
	}

	public function checkMails(Request $request)
	{
		$founded = MailAddress::whereIn('mail', $request->mails)->first();

		if (!!!$founded) {
			return response()->json([
				'isValidate' => true
			]);
		} else {
			return response()->json([
				'message' => "El correo $founded->mail ingresado ya se encuentra registrado, intenta con otro."
			], 401);
		}
	}

    public function changePwd(Request $request)
    {
        if (!(Hash::check($request->old_password, Auth::user()->pwd))) {
            // The passwords matches

			$this->auditService->store([
				'event' => 'UserController@changePwd',
				'subsecid' => 3,
				'error' => true,
				'error_code' => 401,
				'msg' => 'La contraseña ingresada no coincide con nuestros registros.'
			]);

            return response()->json([
                'message' => "La contraseña ingresada no coincide con nuestros registros."
            ], 401);
        }

        else if(strcmp($request->old_password, $request->new_password) == 0){
            // Current password and new password same

			$this->auditService->store([
				'event' => 'UserController@changePwd',
				'subsecid' => 3,
				'error' => true,
				'error_code' => 401,
				'msg' => 'La contraseña no debe ser igual a la anterior.'
			]);

            return response()->json([
                'message' => "La contraseña no debe ser igual a la anterior."
            ], 500);
        } else {
            //Change Password
            $user = Auth::user();
            $user->pwd = bcrypt($request->new_password);
            $user->save();

			$this->auditService->store([
				'event' => 'UserController@changePwd',
				'subsecid' => 3,
				'error' => false,
				'error_code' => 0,
				'msg' => 'La contraseña ha sido actualizada.'
			]);

            return response()->json([
                'message' => "La contraseña ha sido actualizada."
            ], 200);
        }
    }

	public function storePermitBoss(Request $request)
	{
		foreach ($request->permits as $permit) {
			$cat = WorkPermitBoss::create([
				'users_id' => $request->users_id,
				'cat_work_permit_type_id' => $permit
			]);
		}

		return response()->json($cat);
	}

	public function deletePermitBoss(int $id)
	{
		$work_permit = WorkPermitBoss::where('id', $id)->update([
			'deleted' => true
		]);

		return $work_permit;
	}

	public function block(Request $request)
	{
		$blocked = SegUsuario::where('usuarioId', $request->id)->firstOrFail();

		$blocked->bloqueado = !!!$blocked->bloqueado;
		$blocked->save();

		if ($blocked->bloqueado === true) {
			return  response()->json([
				'user' => $blocked,
				'message' => 'El usuario ha sido bloqueado correctamente.'
			]);
		} else {
			return  response()->json([
				'user' => $blocked,
				'message' => 'El usuario ha sido desbloqueado correctamente.'
			]);
		}

	}

	public function delete(Request $request)
	{
		$deleted = SegUsuario::where('usuarioId', $request->id)->firstOrFail();

		$deleted->borrado = !!!$deleted->borrado;
		$deleted->bloqueado =1;
		$deleted->usuario = $deleted->usuario."_del";
		$deleted->save();

		if ($deleted->borrado === true) {
			return  response()->json([
				'user' => $deleted,
				'message' => 'El usuario ha sido eliminado correctamente.'
			]);
		}
	}
}
