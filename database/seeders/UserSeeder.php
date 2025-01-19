<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        User::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
            'status' => 'active',
            'balance' => 0,
        ]);

        // Create kasir user
        User::create([
            'name' => 'Kasir',
            'email' => 'kasir@kasir.com',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'email_verified_at' => now(),
            'status' => 'active',
            'balance' => 0,
        ]);

        // Create owner user
        User::create([
            'name' => 'Owner',
            'email' => 'owner@owner.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
            'email_verified_at' => now(),
            'status' => 'active',
            'balance' => 0,
        ]);
    }
} 