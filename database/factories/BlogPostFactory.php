<?php

namespace Database\Factories;


use Illuminate\Database\Eloquent\Factories\Factory;
use App\Entity\BlogPost;

class BlogPostFactory extends Factory
{
    protected $model = BlogPost::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence(),
            'content' => $this->faker->text(),
        ];
    }
}

// $factory->define(BlogPost::class, function (Faker $faker) {
//     return [
//         'title' => $faker->sentence,
//         'content' => $faker->text,
//     ];
// });
