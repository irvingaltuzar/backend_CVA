<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailAddress extends Model
{
	protected $table = 'mail_address';

	protected $primaryKey = 'id';

	public $autoincrement = true;

	public $timestamps = false;

    protected $guarded = [];
}
