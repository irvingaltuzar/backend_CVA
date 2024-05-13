<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Rule extends Model
{
	use SoftDeletes;

	protected $guarded = [];

	protected $appends = ['first_image_url'];

	public function getFirstImageUrlAttribute()
	{
		return Storage::disk('public')->url("Rules/{$this->id}/{$this->file->file}");
	}

	function file() {
		return $this->hasOne(RuleFile::class, 'rule_id', 'id');
	}
}
