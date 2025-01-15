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
	public function authorizedBySecurity()
	{
		return $this->belongsTo(User::class, 'authorized_security_id');
	}
	public function type()
	{
		return $this->belongsTo(CatWorkPermitType::class, 'cat_work_permit_type_id');
	}
	public function type_highrisk()
	{
		return $this->belongsTo(CatHighRiskJobs::class, 'cat_high_risk_id');
	}

	public function files()
	{
		return $this->hasMany(WorkPermitFile::class, 'work_permit_id')->where("cat_documents_workpermit_id",'<>',6);;
	}
	public function filesImg()
	{
		return $this->hasMany(WorkPermitFile::class, 'work_permit_id')->where("cat_documents_workpermit_id",6);
	}

	public function boss()
	{
		return $this->hasMany(WorkPermitBoss::class, 'cat_work_permit_type_id', 'cat_work_permit_type_id');
	}
	public function brand()
	{
		return $this->belongsTo(CatBrand::class, 'cat_brand_id')->where('deleted', 0);
	}
	function environment() {
		return $this->belongsTo(Environment::class, 'environment_id')->where('deleted', 0);
	}
	public function permitBoss()
	{
		return $this->hasMany(WorkPermitBoss::class, 'users_id')->where('deleted', 0);
	}
	
	protected $casts = [
        'cat_work_permit_type_id' => 'integer',
        'authorized' => 'integer',
        'cat_brand_id' => 'integer',
        'high_risk' => 'boolean',
    ];
}
