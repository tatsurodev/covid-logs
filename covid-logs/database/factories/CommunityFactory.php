<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Community;
use App\User;
use Faker\Generator as Faker;

$factory->define(Community::class, function (Faker $faker) {
    return [
        'name' => $faker->company,
        'user_id' => User::inRandomOrder()->first()->id,
    ];
});
