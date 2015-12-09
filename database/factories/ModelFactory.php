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

$factory->define(App\Eraser::class, function ($faker) {

    $eraserType = $faker->shuffle(['itl','ctl']);
    $passFail = $faker->shuffle(['Success','Fail']);
    $failReason = false;

    if($passFail[0] == 'Fail')
    {
        $failReasons = $faker->shuffle([
            'Unregistered/Unknown',
            'Authentication Exception',
            'Connection Exception',
            'Unknown Exception'
        ]);

        $failReason = $failReasons[0];

    } else {

        $failReason = '';

    }

    if($failReason == 'Unregistered/Unknown')
    {
        $ipAddress = 'Unregistered/Unknown';
    } else {
        $ipAddress = $faker->localIpv4();
    }

    $createdAt = $faker->dateTimeThisYear($max = 'now');
    $updatedAt = $faker->dateTimeBetween($startDate = $createdAt, $endDate = 'now');

    return [
        'phone_id' => $faker->numberBetween($min = 1, $max = 20),
        'ip_address' => $ipAddress,
        'eraser_type' => $eraserType[0],
        'result' => $passFail[0],
        'failure_reason' => $failReason,
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


