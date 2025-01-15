<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBrandRequest;
use App\Models\BucketRole;
use App\Models\CatBrand;
use App\Models\CatBrandDet;
use App\Models\CatWorkPermitType;
use App\Models\DistributionList;
use App\Models\Environment;
use App\Models\User;
use App\Models\WorkPermit;
use App\Repositories\UserRepository;
use App\Services\AuditService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SettingsController extends Controller
{

	private $userRepository, $auditService;

	public function __construct(UserRepository $userRepository, AuditService $auditService)
	{
		$this->userRepository = $userRepository;
		$this->auditService = $auditService;
	}

    public function getBrands()
	{
		$cat_brand_det = CatBrandDet::with(['userType', 'brand.user'])->get();

		return response()->json($cat_brand_det);
	}

	public function getCatBrands()
	{
		$cat_brand_det = CatBrand::where("deleted",0)->get();

		return response()->json($cat_brand_det);
	}

    public function getOwnBrands(int $environment_id)
	{
		$brand= Environment::with([
		'bucket_brands' => function ($query) {
        $query->whereHas('role_brands', function ($roleBrandQuery) {
            $roleBrandQuery->whereHas('type', function ($typeQuery) {
                $typeQuery->whereIn('cat_user_type_id', [3, 4]);
            });
        })->with(['role_brands.type']); // Cargar role_brands con type solo si existen
		}
		])->where('id', $environment_id)->first();	
		return response()->json($brand);
	}

	public function storeBrand(StoreBrandRequest $request)
	{
		$brand = CatBrand::create([
			'description' => $request['description']
		]);

		$cat_brand_det = CatBrandDet::create([
			'cat_user_type_id' => $request['user_type_id'],
			'cat_brand_id' => $brand->id
		]);

		$environment = BucketRole::create([
			'cat_brand_id' => $brand->id,
			'environment_id' => $request->current_environment_id
		]);

		$cat_brand_det->load(['userType', 'brand']);

		return response()->json($cat_brand_det);
	}

	public function getDistributionList()
	{
		$list = DistributionList::with(['detail.brand.user'])->get();

		return response()->json($list);
	}

	public function getQr(String $id)
	{
		$work_permit =  WorkPermit::with(['user' => function ($q) {
							$q->with(['phones', 'mail']);
						}, 'type', 'authorizedBy'])->where('qr_code', $id)->first();

		if (Carbon::now()->gt(Carbon::parse($work_permit->end))) {
			$is_validate = false;
		} else {
			$is_validate = true;
		}

		return view('qr.show', compact('work_permit', 'is_validate'));
	}

	public function storeAudit(Request $request)
	{
		$this->auditService->store([
			'event' => $request['event'],
			'subsecid' => $request['subsecid'],
			'error' => true,
			'error_code' => $request['error_code'],
			'msg' => $request['msg']
		]);
	}

	public function getPermitBoss(Request $request)
	{
		$permits = User::with(['permitBoss' => function ($q) {
						return $q->with(['permitType'])->where('deleted', false);
					}])
					->where('id', $request->id)->first();

		$array_id = $permits->permitBoss->pluck('cat_work_permit_type_id');

		$cat = Environment::with(['bucket_work_permit_type' => function ($q) use ($array_id) {
					return $q->with('work_permit_type')
								->whereHas('work_permit_type', function ($item) use ($array_id) {
									$item->whereNotIn('id', $array_id);
								});
				}])
				->where('id', $request->current_environment)
				->first();

		return response()->json($cat);
	}
}
