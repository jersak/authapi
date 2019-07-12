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
        \App\Models\User::create([
            "name" => "user",
            "email" => "user@email.com",
            "password" => \Illuminate\Support\Facades\Hash::make('secret')
        ]);
    }
}