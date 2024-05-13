<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Release extends Model
{
	use SoftDeletes;

	protected $guarded = [];

	protected $appends = ['first_image_url'];

	public function getFirstImageUrlAttribute()
	{
		return Storage::disk('public')->url("releases/{$this->id}/{$this->file->file}");
	}

	function file() {
		return $this->hasOne(ReleaseFile::class, 'release_id', 'id');
	}
}
