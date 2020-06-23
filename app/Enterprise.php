<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Enterprise extends Model
{
	protected $fillable = ['name'];

	public function inventory()
	{
		return $this->hasOne('App\Inventory');
	}
}
