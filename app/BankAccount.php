<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = ['bank', 'code', 'acccount_number', 'dni','name_enterprise', 'email_enterprise', 'phone_enterprise'];


}
