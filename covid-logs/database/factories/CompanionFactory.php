<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Companion;
use App\User;
use Faker\Generator as Faker;

$factory->define(Companion::class, function (Faker $faker) {
    return [
        'name' => $faker->city,
        'user_id' => User::inRandomOrder()->first()->id,
    ];
});
