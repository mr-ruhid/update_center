<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Əgər bu email-də istifadəçi yoxdursa, yaradır
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@example.com', // Giriş Email
                'password' => Hash::make('password'), // Giriş Şifrəsi: password
                'email_verified_at' => now(),
            ]);
        }
    }
}
