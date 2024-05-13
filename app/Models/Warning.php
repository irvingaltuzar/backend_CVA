<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warning extends Model
{
	protected $table = 'warning';

	protected $primaryKey = 'id';

	public $autoincrement = true;

	public $timestamps = false;

    protected $guarded = [];

	public function sendedBy()
	{
		return $this->belongsTo(User::class, 'sended_by_id');
	}

	public function toName()
	{
		return $this->belongsTo(User::class, 'to');
	}

	public function type()
	{
		return $this->belongsTo(CatWarningType::class, 'warning_type_id');
	}
}
