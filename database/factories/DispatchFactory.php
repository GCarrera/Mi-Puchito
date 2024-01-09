<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Branch;
use App\Dispatch;
use Faker\Generator as Faker;

$factory->define(Dispatch::class, function (Faker $faker) {
    return [
        'status'    => 'espera',
        'branch_id' => Branch::all()->random()->id,
    ];
});
