<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
	protected $table = 'task';

	protected $primaryKey = 'id';

	public $autoincrement = true;

	public $timestamps = false;

    protected $guarded = [];

	protected $dates = [
        'end_date'
    ];

	public function complaint()
	{
		return $this->belongsTo(Complaint::class, 'complaint_id');
	}

	public function thread()
	{
		return $this->hasMany($this, 'parent_id');
	}

	public function files()
	{
		return $this->hasMany(TaskFile::class, 'task_id');
	}
}
