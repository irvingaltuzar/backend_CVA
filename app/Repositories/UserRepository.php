<?php

namespace App\Repositories;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\BucketAdminRole;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\MailAddress;
use App\Models\Signature;
use App\Models\Phone;
use App\Models\SegLogin;
use App\Models\SegSubSeccion;
use App\Models\SegUsuario;
use App\Models\User;
use Illuminate\Support\Str;
use App\Services\SendEmailService;
use GuzzleHttp\Psr7\Request;

class UserRepository
{
	private $sendEmail;

	public function __construct(SendEmailService $sendEmail)
	{
			$this->sendEmail = $sendEmail;
	}

	public function store(int $user_sec_id, StoreUserRequest $request, String $pwd)
	{

		$user = User::create([
					'cat_brand_id' => $request['cat_brand_id'],
					'cat_user_type_id' => $request['cat_user_type_id'],
					'SEG_USUARIOS_usuarioId' => $user_sec_id,
					'birth_date' => $request['birth_date'],
					'position' => $request['position'],
					'show_complaints' => $request->show_complaints,
					'show_warnings' => $request->show_warnings,
					'show_tasks' => 0
				]);

		$this->addPermissions($user, $request, $user_sec_id);

		$this->addEnvironment($user_sec_id, $request);

		sizeof($request->emails) > 0 ? $this->storeEmail($request->emails, $user->id) : false;

		sizeof($request->emails) > 0 ? $this->sendEmail->newUser($request->emails, $request, $pwd) : false;

		sizeof($request->phones) > 0 ? $this->storePhone($request->phones, $user->id) : false;
	}

	function addEnvironment($user, $request) {
		BucketAdminRole::create([
			'SEG_USUARIOS_usuarioId' => $user,
			'environment_id' => $request->current_environment
		]);
	}

	function getCurrentEnvironment() {
		$user = SegUsuario::with(['location_role.admin_environment'])->where('usuarioId', Auth::user()->usuarioId)->first();

		return $user->location_role;
	}

	function getBrandsByEnvironment (int $current_environment) : Object {
		$user = SegUsuario::with(['location_role.admin_environment.bucket_brands'])->where('usuarioId', Auth::user()->usuarioId)->first();

		$location_role = $user->location_role->where('environment_id', $current_environment)->first();

		$environment = $location_role->admin_environment;

		return $environment->bucket_brands->pluck('cat_brand_id');
	}

	public function update(int $user_sec_id, UpdateUserRequest $request)
	{

		$user = User::where('SEG_USUARIOS_usuarioId', $user_sec_id)->update([
					'position' => $request['position'],
					'show_complaints' => $request->show_complaints,
					'show_warnings' => $request->show_warnings,
					'cat_brand_id' => $request->brand_id
				]);

		sizeof($request->emails) > 0 ? $this->updateEmail($request->emails, $user_sec_id) : false;
		sizeof($request->phones) > 0 ? $this->updatePhone($request->phones, $user_sec_id) : false;
	}

	public function updateEmail($emails, int $user_sec_id)
	{
		$user = User::where('SEG_USUARIOS_usuarioId', $user_sec_id)->first();

		$mails = MailAddress::where('users_id', $user->id)->update([
					'deleted' => 1
				]);

		$this->storeEmail($emails, $user->id);
	}

	public function updatePhone(Array $phones, int $user_sec_id)
	{
		$user = User::where('SEG_USUARIOS_usuarioId', $user_sec_id)->first();

		Phone::where('users_id', $user->id)->update([
					'deleted' => 1
				]);

		$this->storePhone($phones, $user->id);
	}

	public function storeEmail(Array $emails, int $user_sec_id)
	{
		foreach ($emails as $email) {
			MailAddress::create([
				'users_id' => $user_sec_id,
				'mail' => $email
			]);
		}
	}

	public function storePhone(Array $phones, int $user_sec_id)
	{
		foreach ($phones as $phone) {
			Phone::create([
				'users_id' => $user_sec_id,
				'phone' => $phone
			]);
		}
	}

	public function getUser()
	{
		return SegUsuario::where('usuarioId', Auth::user()->usuarioId)->with('user')->first();
	}

	public function getSignature()
	{
		$nick_name = $this->getUser()->usuario;

		$signature = Signature::where('users_id', $this->getUser()->user[0]->id)->first();

		if (!!$signature) {
			$url = Storage::disk('public')->url("signatures/{$nick_name}/{$signature->file}.jpg");

			return [
				'signature' => true,
				'url' => $url
			];
		} else {
			return [
				'signature' => false,
				'url' => ''
			];
		}
	}

	public function getSignaturePDF(int $id, String $nick_name)
	{
		$signature = Signature::where('users_id', $id)->first();

		if (!!$signature) {
			$url = Storage::disk('public')->url("signatures/{$nick_name}/{$signature->file}.jpg");

			return [
				'signature' => true,
				'url' => $url
			];
		} else {
			return [
				'signature' => false,
				'url' => ''
			];
		}
	}

	public function addPermissions(User $user, StoreUserRequest $request, $user_sec_id)
	{
		$seg_subsec = SegSubSeccion::get();

		if ($user->cat_user_type_id == 2) {

			$admin_filter = $seg_subsec->filter( function($val) {
				return $val->subsecId !== 2;
			});

			foreach ($admin_filter as $menu) {
				SegLogin::create([
					'usuarioId' => $user_sec_id,
					'subsecId' => $menu->subsecId,
					'loginUsr' =>  $request['usuario'],
					'loginCrud' => 'C,R,U,D,I,E,P'
				]);
			}
		}

		if ($user->cat_user_type_id == 3 || $user->cat_user_type_id == 5 || $user->cat_user_type_id == 6) {

			$admin_filter = $seg_subsec->filter( function($val) {
				return $val->subsecId == 3 || $val->subsecId == 5;
			});

			foreach ($admin_filter as $menu) {
				SegLogin::create([
					'usuarioId' => $user_sec_id,
					'subsecId' => $menu->subsecId,
					'loginUsr' =>  $request['usuario'],
					'loginCrud' => 'C,R,I,E,P'
				]);
			}
		}

		if ($user->cat_user_type_id == 4) {

			$admin_filter = $seg_subsec->filter( function($val) {
				return $val->subsecId >= 3 && $val->secId == 2;
			});

			foreach ($admin_filter as $menu) {
				SegLogin::create([
					'usuarioId' => $user_sec_id,
					'subsecId' => $menu->subsecId,
					'loginUsr' =>  $request['usuario'],
					'loginCrud' => 'C,R,I,E,P'
				]);
			}
		}
	}
}
