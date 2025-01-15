<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BucketUsersBrands extends Model
{
    protected $table = 'bucket_users_brands';

	protected $primaryKey = 'id';

    public $autoincrement = true;

	public $timestamps = false;

    protected $guarded = [];

	protected $appends = [
		'brand_name',
		'environment_id'
	];
	public function getBrandNameAttribute()
	{
		$roleBrand = $this->role_brands;

		return $roleBrand ? $roleBrand->description : 'No Brand';
	}
	public function getEnvironmentIdAttribute()
	{
		$roleBrand = $this->brand_environment;

		return $roleBrand ? $roleBrand->environment_id : '';
	}

	function role_brands() {
		return $this->belongsTo(CatBrand::class, 'cat_brand_id')->where('deleted', 0);
	}
	public function brand()
	{
		return $this->belongsTo(CatBrand::class, 'cat_brand_id')->where('deleted', 0);
	}
	function brand_environment() {
		return $this->belongsTo(BucketRole::class, 'cat_brand_id', 'cat_brand_id')->where('deleted', 0);
	}
	
	public function bucketRole()
	{
		return $this->hasOne(BucketRole::class, 'cat_brand_id', 'cat_brand_id');
	}
	public function user()
	{
		return $this->belongsTo(User::class, 'users_id','id')->where('deleted', 0);
	}

	protected $casts = [
        'cat_brand_id' => 'integer',
    ];
}
