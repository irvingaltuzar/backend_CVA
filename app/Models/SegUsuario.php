<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SegUsuario extends Authenticatable
{
	use HasApiTokens, Notifiable;

	protected $table = 'seg_usuarios';

    protected $keyType = 'integer';

	protected $primaryKey = 'usuarioId';

    public $autoincrement = true;

	public $timestamps = false;

	protected $guarded = [];

	protected $hidden = ['pwd'];

	protected $fillable = [
		'nombre',
		'apepa',
		'apema',
		'usuario',
		'pwd',
	];
	protected $casts = [
        'environment_id' => 'integer',
    ];
	public function getAuthPassword()
	{
		return $this->pwd;
	}

	public function user()
	{
		return $this->hasMany(User::class, 'SEG_USUARIOS_usuarioId')->where('deleted', 0);
	}

	public function permissions()
	{
		return $this->hasMany(SegLogin::class, 'usuarioId');
	}

	function location_role()
	{
		return $this->hasMany(BucketAdminRole::class, 'SEG_USUARIOS_usuarioId')->where('deleted', 0);
	}
}
