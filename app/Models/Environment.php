<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Environment extends Model
{
	public $autoincrement = true;

	public $timestamps = false;

    protected $guarded = [];

	function bucket_brands() {
		return $this->hasMany(BucketRole::class, 'environment_id')->where('deleted', 0);
	}

	function own_brand() {
		return $this->hasOne(BucketRole::class, 'environment_id')->where('deleted', 0);
	}

	function bucket_users() {
		return $this->hasMany(BucketAdminRole::class, 'environment_id')->where('deleted', 0);
	}

	function bucket_rule()  {
		return $this->hasMany(BucketRuleEnvironment::class, 'environment_id')->where('deleted', 0);
	}

	function bucket_release()  {
		return $this->hasMany(BucketRelease::class, 'environment_id');
	}

	function bucket_work_permit_type()  {
		return $this->hasMany(BucketWorkPermitType::class, 'environment_id')->where('deleted', 0);
	}
}
