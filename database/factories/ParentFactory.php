<?php

use Faker\Generator as Faker;
use App\Models\ParentModel as ParentModel;

$factory->define(ParentModel::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->email,
        'phone' => $faker->phoneNumber,
        'password' => bcrypt("123456789"),
    ];
});
