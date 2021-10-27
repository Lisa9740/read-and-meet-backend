<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create("fr_FR");

        $visibility = [true, false, true, true];

        for ($i = 1; $i < 4; $i++) {
            $user = new Profile();
            $user->user_id      = $i;
            $user->description  = $faker->text(50);
            $user->book_liked   = $faker->text(10);
            $user->photo        = $faker->imageUrl();
            $user->is_visible   = $visibility[$i];
            $user->save();
        }


    }
}
