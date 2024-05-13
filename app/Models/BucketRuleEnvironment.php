<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BucketRuleEnvironment extends Model
{
	public $autoincrement = true;

	public $timestamps = false;

	protected $guarded = [];

	function rule() {
		return $this->belongsTo(Rule::class, 'rule_id');
	}
}
