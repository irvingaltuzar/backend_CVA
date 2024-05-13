<?php

namespace App\Http\Controllers;

use App\Models\CatWorkPermitType;
use App\Models\Environment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CatWorkPermitController extends Controller
{
	public function dt(Request $request) : JsonResponse
	{
		$brands = Environment::with(['bucket_work_permit_type.work_permit_type'])
					->where('id', $request->current_environment)
					->first();

		return response()->json($brands);
	}
}
