<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Producto;
use Faker\Generator as Faker;

$factory->define(Producto::class, function (Faker $faker) {
    return [
        'nombre'    => $faker->sentence(3),
        'foto'      => $faker->imageUrl(),
        'stock'     => $faker->numberBetween(0, 300),
        'stock_min'     => $faker->numberBetween(1, 10),
        'precio_menor'  => $faker->randomFloat(2, 10, 500),
        'precio_mayor'  => $faker->randomFloat(2, 7, 300),
    ];
});
