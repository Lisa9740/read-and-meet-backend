<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create("fr_FR");


        for ($i = 1; $i < 4; $i++) {
            $user = new User();
            $user->name         = $faker->firstName();
            $user->email        = $faker->email();
            $user->password     = Hash::make('password');
            $user->user_picture = $faker->imageUrl();
            $user->save();
        }

        \App\Models\User::factory(4)->create();


    }
}
