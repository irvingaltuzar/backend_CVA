<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDistributionListRequest;
use App\Models\CatDistributionListType;
use App\Models\DistributionList;
use App\Models\DistributionListDetail;
use Illuminate\Http\Request;

class DistributionListController extends Controller
{
    public function getCatDistribution()
	{
		$cat = CatDistributionListType::where('deleted', 0)->get();

		return response()->json($cat);
	}

	public function store(StoreDistributionListRequest $request)
	{
		$list = DistributionList::create([
			'name' => $request['description'],
			'cat_distribution_list_type_id' => $request['cat_distribution_type'],
			'deleted' => 0
		]);

		foreach ($request['detail'] as $brand) {
				DistributionListDetail::create([
					'distribution_list_id' => $list->id,
					'cat_brand_id' => $brand
				]);
		}

		return response()->json($list);
	}
}
