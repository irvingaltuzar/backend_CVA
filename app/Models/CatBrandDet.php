<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatBrandDet extends Model
{

	protected $primaryKey = 'id';

	public $autoincrement = true;

	public $timestamps = false;

    protected $guarded = [];

	protected $appends = [
		'type_description'
	];

	public function getTypeDescriptionAttribute()
	{
		return $this->userType->description;
	}

	public function userType()
	{
		return $this->belongsTo(CatUserType::class, 'cat_user_type_id');
	}

	public function brand()
	{
		return $this->belongsTo(CatBrand::class, 'cat_brand_id');
	}
}
