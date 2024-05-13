<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SegAuditoria extends Model
{
    protected $table = 'seg_auditoria';

	protected $primaryKey = 'auditoriaId';

	public $autoincrement = true;

	public $timestamps = false;

    protected $guarded = [];
}
