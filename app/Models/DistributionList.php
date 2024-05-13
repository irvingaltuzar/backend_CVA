<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributionList extends Model
{
	protected $table = 'distribution_list';

	protected $primaryKey = 'id';

	public $autoincrement = true;

	public $timestamps = false;

    protected $guarded = [];

	public function detail()
	{
		return $this->hasMany(DistributionListDetail::class, 'distribution_list_id');
	}
}
