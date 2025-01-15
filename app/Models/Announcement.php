<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
	protected $table = 'messages';

	protected $primaryKey = 'id';

	public $autoincrement = true;

	public $timestamps = false;

    protected $guarded = [];

	protected $dates = [
        'date'
    ];

	public function sendedBy()
	{
		return $this->belongsTo(User::class, 'sended_by_id');
	}

	public function toName()
	{
		return $this->belongsTo(User::class, 'to');
	}

	public function conversation()
	{
		return $this->hasMany($this, 'parent_id');
	}
	public function files()
	{
		return $this->hasMany(AnnouncementFile::class, 'messages_id');
	}
}
