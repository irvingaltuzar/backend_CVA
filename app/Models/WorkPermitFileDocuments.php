<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class WorkPermitFileDocuments extends Model
{
    use SoftDeletes;

    protected $table = 'work_permit_file_documents';

    protected $primaryKey = 'id';

	public $autoincrement = true;

	public $timestamps = true;

    protected $guarded = [];
}
