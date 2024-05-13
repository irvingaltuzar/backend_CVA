<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatWorkPermitType extends Model
{
	protected $table = 'cat_work_permit_type';

	protected $primaryKey = 'id';

	public $autoincrement = true;

	public $timestamps = false;

    protected $guarded = [];
}
