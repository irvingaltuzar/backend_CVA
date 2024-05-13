<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatBrand extends Model
{
	protected $table = 'cat_brand';

	protected $primaryKey = 'id';

	public $autoincrement = true;

	public $timestamps = false;

    protected $guarded = [];

	public function user()
	{
		return $this->hasMany(User::class, 'cat_brand_id');
	}

	function type()
	{
		return $this->hasOne(CatBrandDet::class, 'cat_brand_id', 'id');
	}
}
