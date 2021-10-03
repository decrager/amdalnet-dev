<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Entity\BlogPost;
use Faker\Generator as Faker;

$factory->define(BlogPost::class, function (Faker $faker) {
    return [
        'title' => $faker->sentence,
        'content' => $faker->text,
    ];
});
