<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SegAuditoriaD extends Model
{
    protected $table = 'seg_auditoriad';

	protected $primaryKey = 'auditoriaDId';

	public $autoincrement = true;

	public $timestamps = false;

    protected $guarded = [];
}
