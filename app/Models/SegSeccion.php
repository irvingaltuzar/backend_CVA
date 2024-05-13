<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SegSeccion extends Model
{
    protected $table = 'seg_seccion';

	protected $primaryKey = 'secId';

	public $autoincrement = true;

	public $timestamps = false;

    protected $guarded = [];

	public function subsection()
	{
		return $this->hasMany(SegSubSeccion::class, 'secId');
	}
}
