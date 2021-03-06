<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\BookPost;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create("fr_FR");

            $book = new Book();
            $book->title = $faker->text('10');
            $book->short_description = $faker->text('200');
            $book->isbn_number = $faker->randomDigitNotNull();
            $book->author = $faker->name;

            $book->save();

            $post                  = new Post();
            $post->title           =  $faker->text('20');
            $post->description     =  $faker->text('200');
            $post->user_id         = 1;
            $post->book_id         = $book->id;
            $post->is_visible      = true;
            $post->localisation_id = null;



          //  $user->password     = Hash::make('password');
            $post->save();

    }
}
