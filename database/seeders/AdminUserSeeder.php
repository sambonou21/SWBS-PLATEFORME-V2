<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@swbs.site');
        $password = env('ADMIN_PASSWORD', 'P@rdondieu12+');

        if (! User::where('email', $email)->exists()) {
            User::create([
                'name' => 'Administrateur SWBS',
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'admin',
                'locale' => 'fr',
                'currency' => 'FCFA',
            ]);
        }
    }
}