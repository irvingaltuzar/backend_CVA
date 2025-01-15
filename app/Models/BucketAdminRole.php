<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BucketAdminRole extends Model
{
	public $autoincrement = true;

	public $timestamps = false;

    protected $guarded = [];

	protected $appends = [
		'environment_name',
	];

	protected $casts = [
        'environment_id' => 'integer',
    ];

	public function getEnvironmentNameAttribute()
	{
		return $this->admin_environment->description;
	}

	function role_users()
	{
		return $this->belongsTo(SegUsuario::class, 'SEG_USUARIOS_usuarioId');
	}

	function admin_environment() {
		return $this->belongsTo(Environment::class, 'environment_id')->where('deleted', 0);
	}
}
