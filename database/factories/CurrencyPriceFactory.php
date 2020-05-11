<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\CurrencyPrice;
use Faker\Generator as Faker;

$factory->define(CurrencyPrice::class, function (Faker $faker) {

    return [
        'value' => $faker->randomNumber(),
        'nominal' => 1,
        'date' => $faker->date('Y-m-d'),
        'currency_id' => factory(\App\Models\Currency::class)->create()->id
    ];
});
