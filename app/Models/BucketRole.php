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
		$roleBrand = $this->role_brands;

		return $roleBrand ? $roleBrand->description : 'No Brand';
	}

	function role_brands() {
		return $this->belongsTo(CatBrand::class, 'cat_brand_id')->where('deleted', 0);
	}

	function environment() {
		return $this->belongsTo(Environment::class, 'environment_id')->where('deleted', 0);
	}
	
	public function bucketUsersBrands()
{
    return $this->hasMany(BucketUsersBrands::class, 'cat_brand_id', 'cat_brand_id');
}
	protected $casts = [
        'cat_brand_id' => 'integer',
        'environment_id' => 'integer',
    ];
}
