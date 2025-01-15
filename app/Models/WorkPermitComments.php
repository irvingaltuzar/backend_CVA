<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkPermitComments extends Model
{
    protected $table = 'work_permit_comments';

	protected $primaryKey = 'id';

	public $autoincrement = true;

	public $timestamps = false;

    protected $guarded = [];
    
    protected $casts = [
		'created_at' => "datetime:d-m-Y H:i:s",
		'updated_at' => 'datetime:d-m-Y H:i:s',
	];

	
	public function user()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
	public function work_permit()
	{
		return $this->belongsTo(WorkPermit::class, 'work_permit_id');
	}
}
