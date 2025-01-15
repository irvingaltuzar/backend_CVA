<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Storage;
class AnnouncementFile extends Model
{
    protected $table = 'message_file';

	protected $primaryKey = 'id';

	public $autoincrement = true;

	public $timestamps = false;

    protected $guarded = [];
	protected $appends = ['file_url'];

	
	public function getFileUrlAttribute()
	{

		return Storage::disk('public')->url("Messages/{$this->messages_id}/{$this->file}");
	}

	
}
