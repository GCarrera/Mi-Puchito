<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'email', 'password', 'people_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function warehouses()
    {
        return $this->belongsToMany('App\Warehouse');
    }

    public function people()
    {
        return $this->belongsTo('App\People');
    }

    public function product_ratings()
    {
        return $this->hasMany('App\ProductRating');
    }

    public function sales()
    {
        return $this->hasMany('App\Sale');
    }
}
