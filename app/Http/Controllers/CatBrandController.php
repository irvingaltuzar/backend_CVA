<?php

namespace App\Http\Controllers;

use App\Models\CatBrand;
use App\Models\CatBrandDet;
use App\Models\CatUserType;
use App\Models\CatWarningType;
use App\Models\Environment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CatBrandController extends Controller
{

	public function dt() : JsonResponse
	{
		$brands = CatBrand::get();

		return response()->json($brands);
	}

    public function getByType(int $id, $environment_id)
	{
		$cat_brand = Environment::with(['bucket_brands' => function ($q) use ($id){
			return $q->whereHas('role_brands', function ($query) use ($id){
				return $query->with('type')
						->whereHas('type.userType', function ($q) use ($id){
							$q->where('id', $id);
						});
			});
		}])
		->find($environment_id);

		if (sizeof($cat_brand->bucket_brands) > 0) {
			return response()->json([
				'response_brands' => true,
				'brands' => $cat_brand
			]);
		} else {
			return response()->json([
				'response_brands' => false,
				'brands' => ''
			]);
		}
	}

	public function getWarningType()
	{
		$cat = CatWarningType::get();

		return response()->json($cat);
	}

    public function fetch()
	{
		$cat_brand = CatBrandDet::with('brand')
			->get();

		if (sizeof($cat_brand) > 0) {
			return response()->json([
				'response_brands' => true,
				'brands' => $cat_brand
			]);
		} else {
			return response()->json([
				'response_brands' => false,
				'brands' => ''
			]);
		}
	}

    public function fetchOwn(Request $request)
	{
		$cat_brand = Environment::with(['bucket_brands.role_brands'])
						->find($request->current_environment);

		return response()->json([
			'response_brands' => true,
			'brands' => $cat_brand->bucket_brands
		]);
	}
}
