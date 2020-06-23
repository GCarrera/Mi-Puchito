<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TravelRate extends Model
{
	protected $fillable = ['rate', 'stimated_time', 'sector_id'];

	public function sector()
	{
		return $this->belongsTo('App\Sector');
	}
}
