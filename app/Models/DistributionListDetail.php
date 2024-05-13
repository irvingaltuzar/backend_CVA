<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributionListDetail extends Model
{
	protected $table = 'distribution_list_detail';

	protected $primaryKey = 'id';

	public $autoincrement = true;

	public $timestamps = false;

    protected $guarded = [];

	public function brand()
	{
		return $this->belongsTo(CatBrand::class, 'cat_brand_id');
	}
}
