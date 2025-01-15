<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class WorkPermitBoss extends Model
{
	protected $table = 'work_permit_boss';

	protected $primaryKey = 'id';

	public $autoincrement = true;

	public $timestamps = false;

    protected $guarded = [];

	public function permitType()
	{
		return $this->belongsTo(CatWorkPermitType::class, 'cat_work_permit_type_id');
	}

	public function signer()
	{
		return $this->belongsTo(User::class, 'users_id');
	}
	
	protected $casts = [
        'users_id' => 'integer',
        'cat_work_permit_type_id' => 'integer',
    ];
}
