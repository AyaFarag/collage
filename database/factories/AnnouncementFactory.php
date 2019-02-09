<?php

use Faker\Generator as Faker;

use App\Models\Announcement;
use App\Models\Session;

$factory -> define(Announcement::class, function (Faker $faker) {
    return [
		"session_id" => Session::inRandomOrder() -> first() -> id,
		"title"               => $faker -> word,
		"content"            => $faker -> text,
		"attachment"             => $faker -> url
    ];
});
