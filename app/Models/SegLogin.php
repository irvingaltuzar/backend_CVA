<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SegLogin extends Model
{
    protected $table = 'seg_login';

	protected $primaryKey = 'loginId';

	public $autoincrement = true;

	public $timestamps = false;

    protected $guarded = [];

	// public function setLoginCrudAttribute($value)
	// {
	// 	$this->attributes['loginCrud'] = serialize($value);
	// }

	// public function getLoginCrudAttribute($value)
	// {
	// 	return unserialize($value);
	// }

	public function link()
	{
		return $this->belongsTo(SegSubSeccion::class, 'subsecId');
	}
}
