<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // default user
        User::factory()->create([
            'email' => 'test@gmail.com',
            'password' => '123',
            'is_admin' => true,
        ]);

        // 50 regular users
        User::factory(50)->create();

        // 50 unverified users
        User::factory(50)->unverified()->create();
    }
}
