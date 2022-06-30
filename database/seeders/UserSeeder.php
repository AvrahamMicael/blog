<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $password = bcrypt('123123123');

        User::factory()
            ->admin()
            ->create([
                'email' => 'admin@test.com',
                'password' => $password
            ]);

        User::factory()
            ->create([
                'email' => 'micael@test.com',
                'password' => $password
            ]);
    }
}
