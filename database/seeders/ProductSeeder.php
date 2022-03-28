<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Post;
use App\Models\Product;
use Illuminate\Database\Seeder;


class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create("fr_FR");

        for ($i=0; $i <= 20; $i++){
            $product = new Product();
            $product->name = $faker->text('20');
            $product->description = $faker->text('200');
            $product->price = $faker->randomNumber(2);
            $product->image = "";


            //  $user->password     = Hash::make('password');
            $product->save();
        }

    }
}
