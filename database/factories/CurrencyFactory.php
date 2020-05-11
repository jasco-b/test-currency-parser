<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Currency;
use Faker\Generator as Faker;

$factory->define(Currency::class, function (Faker $faker) {
    return [
        'valuteID' => $faker->text(10),
        'charCode' => substr($faker->text(5), 3),
        'numCode' => $faker->randomNumber(3, true),
    ];
});
