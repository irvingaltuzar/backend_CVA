<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkPermitPdf extends Model
{
	protected $table = 'work_permit_pdf';

	protected $primaryKey = 'id';

	public $autoincrement = true;

	public $timestamps = false;

    protected $guarded = [];
}
