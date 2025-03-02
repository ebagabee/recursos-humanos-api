<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $user = User::updateOrCreate(
            [
                "email" => "ebagabe.2025@gmail.com"
            ],
            [
                'name' => "Gabriel",
                'password' => "794613",
                'role' => "admin",
            ]
        );

        $user->save();
    }
}