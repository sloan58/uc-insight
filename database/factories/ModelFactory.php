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

use Carbon\Carbon;

$factory->define(App\User::class, function ($faker) {

    $createdAt = $faker->dateTimeThisYear($max = 'now');
    $updatedAt = $faker->dateTimeBetween($startDate = $createdAt, $endDate = 'now');

    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
        'created_at' => $createdAt,
        'updated_at' => $updatedAt
    ];
});

$factory->define(App\Phone::class, function ($faker) {

    $createdAt = $faker->dateTimeThisYear($max = 'now');
    $updatedAt = $faker->dateTimeBetween($startDate = $createdAt, $endDate = 'now');

    return [
        'mac' => 'SEP' . strtoupper($faker->regexify('[0-9A-Fa-f]{12}')),
        'description' => $faker->realText($maxNbChars = 30, $indexSize = 2),
        'created_at' => $createdAt,
        'updated_at' => $updatedAt
    ];
});

$factory->define(App\Cluster::class, function ($faker){
    return [
        'name' => $faker->word(),
        'ip' => $faker->localIpv4(),
        'username' => $faker->name,
        'password' => bcrypt(str_random(10))
    ];
});


