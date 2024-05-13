<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BucketRole extends Model
{
	public $autoincrement = true;

	public $timestamps = false;

    protected $guarded = [];

	protected $appends = [
		'brand_name'
	];

	public function getBrandNameAttribute()
	{
		return $this->role_brands->description;
	}

	function role_brands() {
		return $this->belongsTo(CatBrand::class, 'cat_brand_id')->where('deleted', 0);
	}

	function environment() {
		return $this->belongsTo(Environment::class, 'environment_id')->where('deleted', 0);
	}
}
