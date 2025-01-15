<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatDocumentsWorkPermit extends Model
{

	protected $table = 'cat_documents_work_permit';

	protected $primaryKey = 'id';

	public $autoincrement = true;

	public $timestamps = true;

    protected $guarded = [];
}