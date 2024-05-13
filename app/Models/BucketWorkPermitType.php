<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BucketWorkPermitType extends Model
{
	public $autoincrement = true;

	public $timestamps = false;

    protected $guarded = [];

	function work_permit_type() {
		return $this->belongsTo(CatWorkPermitType::class, 'work_permit_type_id');
	}
}
