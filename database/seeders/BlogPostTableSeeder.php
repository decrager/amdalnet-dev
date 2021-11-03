<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Entity\BlogPost;

class BlogPostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(BlogPost::class, 10)->create();
    }
}
