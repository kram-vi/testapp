<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Todo;
use Faker\Generator as Faker;

$factory->define(Todo::class, function (Faker $faker) {
    return [
        'todo' => $faker->sentence,
        'description' => $faker->paragraph,
        'category' => $faker->word,
        'user_id' => factory('App\User')->create()->id,
    ];
});
