<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class UserWarehouse extends Pivot
{
    protected $fillable = ['user_id', 'warehouse_id'];

}
