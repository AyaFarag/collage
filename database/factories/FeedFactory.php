<?php

use Faker\Generator as Faker;
usE App\Models\Feed as Feed;

$factory->define(Feed::class, function (Faker $faker) {
    return [
        'title' => $faker->name,
        'sub-title' => $faker->name,
        'details' => $faker->text,
    ];
});
