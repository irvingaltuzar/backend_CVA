<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SegSubSeccion extends Model
{
    protected $table = 'seg_subseccion';

	protected $primaryKey = 'subsecId';

	public $autoincrement = true;

	public $timestamps = false;

    protected $guarded = [];
}
