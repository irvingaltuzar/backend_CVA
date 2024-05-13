<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BucketRelease extends Model
{
	use SoftDeletes;

	protected $guarded = [];

	function release() {
		return $this->belongsTo(Release::class, 'release');
	}
}
