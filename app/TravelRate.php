<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TravelRate extends Model
{
	use SoftDeletes;

	protected $fillable = ['rate', 'stimated_time', 'sector_id'];

	public function sector()
	{
		return $this->belongsTo('App\Sector');
	}
}
