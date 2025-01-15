<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* ==========================================================================
	Authentication routes
========================================================================== */


Route::middleware(['auth:sanctum'])->get('/user', function () {
	return Auth::user()->load('user');
});

/* ==========================================================================
	Tool routes
========================================================================== */

Route::middleware(['auth:sanctum'])->get('brand/get-list', [App\Http\Controllers\CatBrandController::class, 'dt']);
Route::middleware(['auth:sanctum'])->get('brand/get-by-type/{id}/{current_environment_id}', [App\Http\Controllers\CatBrandController::class, 'getByType']);
Route::middleware(['auth:sanctum'])->post('brand/post-by-type', [App\Http\Controllers\CatBrandController::class, 'postByType']);
Route::middleware(['auth:sanctum'])->get('brand/fetch-own', [App\Http\Controllers\CatBrandController::class, 'fetchOwn']);
Route::middleware(['auth:sanctum'])->get('brand/fetch', [App\Http\Controllers\CatBrandController::class, 'fetch']);
Route::middleware(['auth:sanctum'])->post('brand/getBrandsUserEnv', [App\Http\Controllers\CatBrandController::class, 'getBrandsUserEnv']);
Route::middleware(['auth:sanctum'])->post('brand/store', [App\Http\Controllers\SettingsController::class, 'storeBrand']);
Route::middleware(['auth:sanctum'])->get('brand/get-warning-type', [App\Http\Controllers\CatBrandController::class, 'getWarningType']);
Route::middleware(['auth:sanctum'])->get('cat-work/get-list', [App\Http\Controllers\CatWorkPermitController::class, 'dt']);
Route::middleware(['auth:sanctum'])->get('/settings/get-distribution-list', [App\Http\Controllers\SettingsController::class, 'getDistributionList']);
Route::middleware(['auth:sanctum'])->get('/settings/get-permit-boss', [App\Http\Controllers\SettingsController::class, 'getPermitBoss']);
Route::middleware(['auth:sanctum'])->get('/dashboard/get-stats', [App\Http\Controllers\DashboardController::class, 'getAdminStats']);
Route::middleware(['auth:sanctum'])->get('/dashboard/get-user-stats', [App\Http\Controllers\DashboardController::class, 'getUserStats']);
Route::middleware(['auth:sanctum'])->post('/download', [App\Http\Controllers\DownloadFilesController::class, 'downloadPDF']);

/* ==========================================================================
	WorkPermit routes
========================================================================== */

Route::middleware(['auth:sanctum'])->post('work-permit/store', [App\Http\Controllers\WorkPermitController::class, 'store']);
Route::middleware(['auth:sanctum'])->post('work-permit/storeFileDoc', [App\Http\Controllers\WorkPermitController::class, 'storeFileDoc']);
Route::middleware(['auth:sanctum'])->post('work-permit/update', [App\Http\Controllers\WorkPermitController::class, 'update']);
Route::middleware(['auth:sanctum'])->post('work-permit/addComments', [App\Http\Controllers\WorkPermitController::class, 'addComments']);
Route::middleware(['auth:sanctum'])->post('work-permit/getComments', [App\Http\Controllers\WorkPermitController::class, 'getComments']);
Route::middleware(['auth:sanctum'])->post('work-permit/deleteFiles', [App\Http\Controllers\WorkPermitController::class, 'DeleteFilesWorkPermit']);
Route::middleware(['auth:sanctum'])->get('work-permit/supplier/get-list', [App\Http\Controllers\WorkPermitController::class, 'fetchSupplierPermit']);
Route::middleware(['auth:sanctum'])->get('work-permit/get-list', [App\Http\Controllers\WorkPermitController::class, 'dt']);
Route::middleware(['auth:sanctum'])->get('work-permit/get-list-high-risk', [App\Http\Controllers\WorkPermitController::class, 'dt']);
Route::middleware(['auth:sanctum'])->get('work-permit/get-security-list', [App\Http\Controllers\WorkPermitController::class, 'fetchSecurityPermit']);
Route::middleware(['auth:sanctum'])->get('work-permit/getCatHighRisk', [App\Http\Controllers\WorkPermitController::class, 'getCatHighRisk']);
Route::middleware(['auth:sanctum'])->post('work-permit/authorize', [App\Http\Controllers\WorkPermitController::class, 'authorizePermit']);
Route::middleware(['auth:sanctum'])->post('work-permit/authorize_security', [App\Http\Controllers\WorkPermitController::class, 'authorizeSecurityPermit']);
Route::middleware(['auth:sanctum'])->post('work-permit/cancel', [App\Http\Controllers\WorkPermitController::class, 'cancelPermit']);
Route::middleware(['auth:sanctum'])->post('work-permit/reasign', [App\Http\Controllers\WorkPermitController::class, 'ReasignPermit']);
Route::middleware(['auth:sanctum'])->post('work-permit/show-pdf', [App\Http\Controllers\WorkPermitController::class, 'showPdf']);
Route::middleware(['auth:sanctum'])->get('work-permit/downloadZip', [App\Http\Controllers\WorkPermitController::class, 'downloadZip']);
Route::middleware(['auth:sanctum'])->get('work-permit/document-url', [App\Http\Controllers\WorkPermitController::class, 'getFileUrl']);
Route::middleware(['auth:sanctum'])->post('work-permit/active_highrisk', [App\Http\Controllers\WorkPermitController::class, 'active_highrisk']);

/* ==========================================================================
	Signature routes
========================================================================== */

Route::middleware(['auth:sanctum'])->post('signature/store', [App\Http\Controllers\SignatureController::class, 'store']);
Route::middleware(['auth:sanctum'])->get('signature/get', [App\Http\Controllers\SignatureController::class, 'get']);

/* ==========================================================================
	Announcement routes
========================================================================== */

Route::middleware(['auth:sanctum'])->post('announcement/store', [App\Http\Controllers\AnnouncementController::class, 'store']);
Route::middleware(['auth:sanctum'])->post('announcement/send-message', [App\Http\Controllers\AnnouncementController::class, 'sendMessage']);
Route::middleware(['auth:sanctum'])->get('announcement/get-by-user', [App\Http\Controllers\AnnouncementController::class, 'getByUser']);
Route::middleware(['auth:sanctum'])->get('announcement/get-admin-data/{type}/{environment_id}', [App\Http\Controllers\AnnouncementController::class, 'getAdminData']);
Route::middleware(['auth:sanctum'])->get('announcement/get-user-data', [App\Http\Controllers\AnnouncementController::class, 'getUserData']);
Route::middleware(['auth:sanctum'])->get('announcement/get-detail/{id}', [App\Http\Controllers\AnnouncementController::class, 'getDetail']);

/* ==========================================================================
	Sidebar routes
========================================================================== */

Route::get('sidebar/get-data', [App\Http\Controllers\UserController::class, 'getSidebar']);
Route::get('sidebar/get-subsec', [App\Http\Controllers\UserController::class, 'getSubSec']);

/* ==========================================================================
	Permission routes
========================================================================== */

Route::middleware(['auth:sanctum'])->post('permission/store', [App\Http\Controllers\UserController::class, 'storePermission']);

/* ==========================================================================
	Warnings routes
========================================================================== */

Route::middleware(['auth:sanctum'])->post('warnings/store', [App\Http\Controllers\WarningController::class, 'store']);
Route::get('warnings/get-data', [App\Http\Controllers\WarningController::class, 'fetchData']);
Route::get('warnings/get-data-by-user', [App\Http\Controllers\WarningController::class, 'fetchDataByUser']);

/* ==========================================================================
	Rules routes
========================================================================== */

Route::middleware(['auth:sanctum'])->post('rules/store', [App\Http\Controllers\RuleController::class, 'store']);
Route::middleware(['auth:sanctum'])->get('rules/get-list', [App\Http\Controllers\RuleController::class, 'getData']);
Route::middleware(['auth:sanctum'])->get('rules/fetch', [App\Http\Controllers\RuleController::class, 'fetchRules']);

/* ==========================================================================
	Releases routes
========================================================================== */

Route::middleware(['auth:sanctum'])->post('release/store', [App\Http\Controllers\ReleaseController::class, 'store']);
Route::middleware(['auth:sanctum'])->post('release/delete', [App\Http\Controllers\ReleaseController::class, 'delete']);
Route::middleware(['auth:sanctum'])->post('release/update', [App\Http\Controllers\ReleaseController::class, 'update']);
Route::middleware(['auth:sanctum'])->get('release/fetch', [App\Http\Controllers\ReleaseController::class, 'fetchData']);

/* ==========================================================================
	Tools routes
========================================================================== */

Route::get('/brands/get-data/{id}', [App\Http\Controllers\SettingsController::class, 'getOwnBrands']);
Route::get('/brands/get-data-cat', [App\Http\Controllers\SettingsController::class, 'getCatBrands']);
Route::post('swap/environment', [App\Http\Controllers\SwapperController::class, 'environment']);

Route::get('/unauthenticated', function () {
    return response()->json([
		"message" => "unauthenticated"
	]);
})->name('api.unauthenticated');

/* ==========================================================================
	Users routes
========================================================================== */

Route::middleware(['auth:sanctum'])->get('user/get-list', [App\Http\Controllers\UserController::class, 'dt']);
Route::middleware(['auth:sanctum'])->get('user/get-permissions', [App\Http\Controllers\UserController::class, 'getPermissions']);
Route::middleware(['auth:sanctum'])->get('user/get-user-type', [App\Http\Controllers\UserController::class, 'getUserType']);
Route::middleware(['auth:sanctum'])->get('user/get-user-type-brand', [App\Http\Controllers\UserController::class, 'getUserTypeBrand']);
Route::middleware(['auth:sanctum'])->get('user/get-admin-users', [App\Http\Controllers\UserController::class, 'getAdminUsers']);
Route::middleware(['auth:sanctum'])->get('user/get-user-by-brand/{id}', [App\Http\Controllers\UserController::class, 'getUserByBrand']);
Route::middleware(['auth:sanctum'])->get('user/get-user-by-warning/{id}', [App\Http\Controllers\UserController::class, 'getUserByWarning']);
Route::middleware(['auth:sanctum'])->post('user/store', [App\Http\Controllers\UserController::class, 'store']);
Route::middleware(['auth:sanctum'])->post('user/update', [App\Http\Controllers\UserController::class, 'update']);
Route::middleware(['auth:sanctum'])->post('user/settings/store-permit-boss', [App\Http\Controllers\UserController::class, 'storePermitBoss']);
Route::middleware(['auth:sanctum'])->post('user/settings/block', [App\Http\Controllers\UserController::class, 'block']);
Route::middleware(['auth:sanctum'])->post('user/settings/delete', [App\Http\Controllers\UserController::class, 'delete']);
Route::middleware(['auth:sanctum'])->get('user/settings/delete-permit-boss/{id}', [App\Http\Controllers\UserController::class, 'deletePermitBoss']);
Route::middleware(['auth:sanctum'])->get('user/check-nickname/{nickname}', [App\Http\Controllers\UserController::class, 'checkNickname']);
Route::middleware(['auth:sanctum'])->post('user/check-mails', [App\Http\Controllers\UserController::class, 'checkMails']);
Route::middleware(['auth:sanctum'])->get('user/check-current-environment', [App\Http\Controllers\UserController::class, 'checkEnvironment']);
Route::middleware(['auth:sanctum'])->get('user/getEnvironments', [App\Http\Controllers\UserController::class, 'getEnvironments']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('user/change-pwd', [App\Http\Controllers\UserController::class, 'changePwd']);
});

/* ==========================================================================
	complaints routes
========================================================================== */

Route::middleware(['auth:sanctum'])->get('complaints/get-admin-complaints', [App\Http\Controllers\ComplaintController::class, 'getAdminComplaints']);
Route::middleware(['auth:sanctum'])->get('complaints/stats', [App\Http\Controllers\ComplaintController::class, 'getAdminComplaints']);
Route::middleware(['auth:sanctum'])->post('complaints/change', [App\Http\Controllers\ComplaintController::class, 'changeStatus']);

/* ==========================================================================
	tasks routes
========================================================================== */

Route::middleware(['auth:sanctum'])->get('tasks/get-admin-tasks', [App\Http\Controllers\TaskController::class, 'getAdminTasks']);
Route::middleware(['auth:sanctum'])->post('tasks/store-admin-task', [App\Http\Controllers\TaskController::class, 'storeAdminTask']);
Route::middleware(['auth:sanctum'])->post('tasks/store', [App\Http\Controllers\TaskController::class, 'store']);
Route::middleware(['auth:sanctum'])->post('tasks/store-thread', [App\Http\Controllers\TaskController::class, 'storeThread']);
Route::middleware(['auth:sanctum'])->post('task/change', [App\Http\Controllers\TaskController::class, 'changeStatus']);

/* ==========================================================================
	Distribution List routes
========================================================================== */

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/distribution-list/get-cat-distribution', [App\Http\Controllers\DistributionListController::class, 'getCatDistribution']);
    Route::post('/distribution-list/store', [App\Http\Controllers\DistributionListController::class, 'store']);
});
