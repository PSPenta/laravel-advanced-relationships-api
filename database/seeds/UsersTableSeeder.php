<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\User::class)->create()->each(function ($user) {
            $user->roles()->save(factory(App\Models\Role::class)->make());
        });
    }
}
