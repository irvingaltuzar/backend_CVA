<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $table = 'complaint';

	protected $primaryKey = 'id';

	public $autoincrement = true;

	public $timestamps = false;

    protected $guarded = [];

	public function user()
	{
		return $this->belongsTo(User::class, 'asigned_to_id');
	}

	public function files()
	{
		return $this->hasMany(ComplaintFile::class, 'complaint_id');
	}

	public function tasks()
	{
		return $this->hasMany(Task::class, 'complaint_id');
	}
}
