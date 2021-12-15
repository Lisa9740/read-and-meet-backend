<?php

namespace Database\Seeders;

use App\Models\Profile;
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
        $visibility = [true, false, true, true];

        for ($i = 1; $i < 4; $i++) {
            $profile = new Profile();
            $user = new User();

            $profile->description  = $faker->text(50);
            $profile->book_liked   = $faker->text(10);
            $profile->photo        = $faker->imageUrl();
            $profile->is_visible   = $visibility[$i];
            $profile->save();

            $user->profile_id    = $profile->id;
            $user->name         = $faker->firstName();
            $user->email        = $faker->email();
            $user->password     = Hash::make('password');
            $user->user_picture = $faker->imageUrl();
            $user->gender = 'male';
            $user->age = '24';
            $user->save();
        }


        //\App\Models\User::factory(4)->create();

    }
}
