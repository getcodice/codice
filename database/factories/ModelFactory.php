<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(Codice\Label::class, function (Faker\Generator $faker) {
    return [
        'user_id' => 1,
        'name' => $faker->word,
    ];
});

$factory->define(Codice\Note::class, function (Faker\Generator $faker) {
    return [
        'user_id' => 1,
        'content' => $faker->paragraph,
        'expires_at' => null,
    ];
});

$factory->define(Codice\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
        'options' => Codice\User::$defaultOptions,
    ];
});

