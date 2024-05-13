<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkPermit extends Model
{
	protected $table = 'work_permit';

	protected $primaryKey = 'id';

	public $autoincrement = true;

	public $timestamps = false;

    protected $guarded = [];

	public function user()
	{
		return $this->belongsTo(User::class, 'responsable_id');
	}

	public function authorizedBy()
	{
		return $this->belongsTo(User::class, 'authorized_by_id');
	}

	public function type()
	{
		return $this->belongsTo(CatWorkPermitType::class, 'cat_work_permit_type_id');
	}

	public function files()
	{
		return $this->hasMany(WorkPermitFile::class, 'work_permit_id');
	}

	public function boss()
	{
		return $this->hasMany(WorkPermitBoss::class, 'cat_work_permit_type_id', 'cat_work_permit_type_id');
	}
}
