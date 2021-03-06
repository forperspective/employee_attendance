<?php

use App\User;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'department' => Str::random(10),
        'designation' => Str::random(10),
        'contact_number' => random_int(1,10),
        'email_verified_at' => now(),
        'password' => '123456', // password
        'remember_token' => Str::random(10),
    ];
});
