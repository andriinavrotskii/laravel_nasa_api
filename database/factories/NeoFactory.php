<?php

use Faker\Generator as Faker;
use App\Model\Neo;

$factory->define(Neo::class, function (Faker $faker) {
    return [
        'reference' => $faker->unique()->randomNumber($nbDigits = 7, $strict = false),
        'date' => $faker->date($format = 'Y-m-d', $max = 'now', $min = '-3 day'),
        'name' => $faker->name(),
        'speed' => $faker->randomFloat($nbMaxDecimals = 10, $min = 0, $max = 999999),
        'is_hazardous' => $faker->boolean(),
    ];
});
