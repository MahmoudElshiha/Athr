<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class CartSeeder extends Seeder
{
    public function run(): void
    {
        foreach (User::all() as $user) {
            $user->cart()->create();
        }
    }
}
