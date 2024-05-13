<?php

use App\Models\Announcement;
use App\Models\BucketAdminRole;
use App\Models\BucketRole;
use App\Models\CatBrand;
use App\Models\CatBrandDet;
use App\Models\CatWorkPermitType;
use App\Models\Complaint;
use App\Models\DistributionList;
use App\Models\Environment;
use App\Models\MailAddress;
use App\Models\SegUsuario;
use App\Models\Task;
use App\Models\User;
use App\Models\WorkPermit;
use App\Models\WorkPermitBoss;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Repositories\UserRepository;
use GuzzleHttp\Client;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('test/', function () {

	$environment = 5;
	$mails = collect();

	$work_permit = WorkPermit::find(9937);

	$collect = $work_permit->load(['user' => function ($q) {
		return $q->with(['brand']);
	}, 'type', 'boss' => function ($query) use ($environment){
		$query->with(['signer' => function ($q) use ($environment){
			$q->with(['user_environment', 'mail'])
				->whereHas('user_environment', function ($query) use ($environment){
					$query->where('environment_id', $environment);
				});
		}])->where('deleted', 0);
	}]);

	$mail = $collect->boss->map( function($val) use ($mails){
		return $val->signer != null ? $mails->push($val->signer->mail->mail) : false ;
	});

	return $mail;

});

Route::get('queue-email', function(){

	$users = User::with('brand')
	->where('show_complaints', true)
	->get();

	return response()->json($users);

	// $mails = collect();

	// $permit = WorkPermit::with(['user' => function ($q) {
	// 	return $q->with(['brand']);
	// }, 'type', 'boss' => function ($query) {
	// 	return $query->with(['signer.mail'])->where('deleted', 0);
	// }])
	// ->where('deleted', 0)->find(45);

	// $collect = $permit->load(['user' => function ($q) {
	// 	return $q->with(['brand']);
	// }, 'type', 'boss.signer.mail']);


	// // $permit->boss->map( function($val) use ($mails){
	// // 	return $mails->push($val->signer->mail->mail);
	// // });

    // $email_list['email'] = ['arturo.jara@grupodmi.com.mx', 'carlos.montejo@grupodmi.com.mx'];

	// $a = dispatch(new \App\Jobs\SendTokenJob($email_list, 'asdhjfsdahjfgsahf'))->afterResponse();

	// return response()->json($a);

    // dispatch(new \App\Jobs\QueueJob($mails, $permit))->afterResponse();
});

Route::get('/linkstorage', function () {
    Artisan::call('storage:link');
});

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
	return Auth::user();
});
/* ==========================================================================
	Sidebar routes
========================================================================== */

Route::get('sidebar/get-data', [App\Http\Controllers\UserController::class, 'getSidebar']);
Route::get('sidebar/get-subsec', [App\Http\Controllers\UserController::class, 'getSubSec']);

Route::get('get-user', [App\Http\Controllers\LoginController::class, 'getUser']);
Route::post('user/check-account', [App\Http\Controllers\LoginController::class, 'checkAccount']);
Route::post('user/check-user', [App\Http\Controllers\LoginController::class, 'checkUser']);
Route::post('user/reset-pwd', [App\Http\Controllers\LoginController::class, 'changePwd']);
Route::post('user/change-pwd', [App\Http\Controllers\LoginController::class, 'changePassword']);
Route::get('user/check-token/{id}', [App\Http\Controllers\ForgotPasswordController::class, 'checkToken']);

/* ==========================================================================
Complaints routes
========================================================================== */

Route::get('/user/get-user-complaint', [App\Http\Controllers\UserController::class, 'getUserComplaint']);
Route::post('/complaint/store', [App\Http\Controllers\ComplaintController::class, 'store']);

/* ==========================================================================
Brands routes
========================================================================== */

Route::get('/brands/get-data', [App\Http\Controllers\SettingsController::class, 'getBrands']);

/* ==========================================================================
QR routes
========================================================================== */

Route::get('/qr/show-info/{id}', [App\Http\Controllers\SettingsController::class, 'getQr']);

Route::post('/settings/store-audit', [App\Http\Controllers\SettingsController::class, 'storeAudit']);


Route::get('test-pdf', [App\Http\Controllers\WorkPermitController::class, 'testPDF']);

Route::middleware(['auth:sanctum'])->get('user/get-list', [App\Http\Controllers\UserController::class], 'dt');

Route::post('login', [App\Http\Controllers\LoginController::class, 'login']);
Route::post('logout', [App\Http\Controllers\LoginController::class, 'logout']);
