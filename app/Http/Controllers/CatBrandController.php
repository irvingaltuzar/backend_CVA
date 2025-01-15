<?php

namespace App\Http\Controllers;

use App\Models\CatBrand;
use App\Models\CatBrandDet;
use App\Models\CatUserType;
use App\Models\CatWarningType;
use App\Models\Environment;
use App\Models\BucketUsersBrands;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\DB;

class CatBrandController extends Controller
{
	
	private $userRepository;
	public function __construct(UserRepository $userRepository)
	{
		$this->userRepository = $userRepository;
	}

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
	public function postByType(Request $request)
	{
		$id = $request['id'];
		$environment_ids = $request['environment_ids']; // Espera un array de IDs
	
			// Verifica si environment_ids es un array y no está vacío
			if (!is_array($environment_ids) || empty($environment_ids)) {
				return response()->json([
					'response_brands' => false,
					'brands' => 'Invalid environment_ids input'
				]);
			}

			// Busca los entornos con los IDs proporcionados y carga sus bucket_brands
			$cat_brands = Environment::with(['bucket_brands' => function ($q) use ($id) {
				return $q->whereHas('role_brands', function ($query) use ($id) {
					return $query->with('type')
								->whereHas('type.userType', function ($q) use ($id) {
									$q->where('id', $id);
								});
				});
			}])
			->whereIn('id', $environment_ids)
			->get();

			// Inicializa un array para almacenar las marcas concatenadas
			$all_brands = collect();

			// Recorre los entornos y concatena las marcas
			foreach ($cat_brands as $environment) {
				if ($environment->bucket_brands->isNotEmpty()) {
					foreach ($environment->bucket_brands as $brand) {
						// Añade el nombre del environment a cada bucket_brand
						$brand->environment = $environment->only(['id', 'description']);
						$all_brands->push($brand);
					}
				}
			}

			// Filtra las marcas duplicadas (si es necesario)
			$unique_brands = $all_brands->unique('id')->values();

			// Retorna la respuesta en formato JSON
			return response()->json([
				'response_brands' => $unique_brands->isNotEmpty(),
				'brands' => $unique_brands
			]);
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
	public function getBrandsUserEnv(Request $request){
		$environment_id = $request->current_environment;
		$users_id=$this->userRepository->getUser()->user[0]->id;

		$brandsUser = BucketUsersBrands::where('users_id', $users_id)
    ->whereHas('bucketRole', function($query) use ($environment_id) {
        $query->where('environment_id', $environment_id);
    })
      ->get();
		return response()->json($brandsUser);
	}
	
}
