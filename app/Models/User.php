<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

	protected $primaryKey = 'id';

	public $autoincrement = true;

	public $timestamps = false;

    protected $guarded = [];

	protected $dates = [
        'birth_date'
    ];

	protected $appends = [
		'user_name',
		'brand_name'
	];
	protected $casts = [
        'cat_user_type_id' => 'integer',
        'show_complaints' => 'boolean',
		'show_warnings'=> 'boolean',
    ];
	public function brand()
	{
		return $this->belongsTo(CatBrand::class, 'cat_brand_id')->where('deleted', 0);
	}
	public function allbrands()
	{
		return $this->hasMany(BucketUsersBrands::class, 'users_id');
	}

	public function userSec()
	{
		return $this->belongsTo(SegUsuario::class, 'SEG_USUARIOS_usuarioId');
	}

	public function getUserNameAttribute()
	{
		return ($this->userSec != "") ? "{$this->userSec->nombre} {$this->userSec->apepa}" : "";
	}

	public function getBrandNameAttribute()
	{
		$roleBrand = $this->brand;

		return $roleBrand ? $roleBrand->description : 'No Brand';
	}

	public function phones()
	{
		return $this->hasMany(Phone::class, 'users_id')->where('deleted', 0);
	}

	public function mail()
	{
		return $this->hasOne(MailAddress::class, 'users_id')->where('deleted', 0);
	}

	public function mails()
	{
		return $this->hasMany(MailAddress::class, 'users_id')->where('deleted', 0);
	}

	function brand_environment() {
		return $this->belongsTo(BucketRole::class, 'cat_brand_id', 'cat_brand_id')->where('deleted', 0);
	}

	function user_environment() {
		return $this->belongsTo(BucketAdminRole::class, 'SEG_USUARIOS_usuarioId', 'SEG_USUARIOS_usuarioId')->where('deleted', 0);
	}

	public function type()
	{
		return $this->belongsTo(CatUserType::class, 'cat_user_type_id')->where('deleted', 0);
	}

	public function permitBoss()
	{
		return $this->hasMany(WorkPermitBoss::class, 'users_id')->where('deleted', 0);
	}
}
