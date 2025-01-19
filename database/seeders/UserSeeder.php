<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Create admin if it doesn't exist
        if (!User::where('email', 'admin@admin.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
                'status' => 'active',
                'balance' => 0,
            ]);
        }

        // Create some students if they don't exist
        $studentEmails = [
            'student1@example.com',
            'student2@example.com',
            'student3@example.com',
            'student4@example.com',
            'student5@example.com',
        ];

        foreach ($studentEmails as $email) {
            if (!User::where('email', $email)->exists()) {
                User::create([
                    'name' => 'Student ' . substr($email, 7, 1),
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'role' => 'student',
                    'email_verified_at' => now(),
                    'status' => 'active',
                    'balance' => rand(10000, 100000),
                    'student_id' => 'STD' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                    'class' => ['10A', '10B', '11A', '11B', '12A', '12B'][rand(0, 5)],
                ]);
            }
        }

        // Create some teachers if they don't exist
        $teacherEmails = [
            'teacher1@example.com',
            'teacher2@example.com',
            'teacher3@example.com',
        ];

        foreach ($teacherEmails as $email) {
            if (!User::where('email', $email)->exists()) {
                User::create([
                    'name' => 'Teacher ' . substr($email, 7, 1),
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'role' => 'teacher',
                    'email_verified_at' => now(),
                    'status' => 'active',
                    'balance' => rand(50000, 200000),
                    'employee_id' => 'TCH' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                    'subject' => ['Mathematics', 'Physics', 'Biology', 'Chemistry', 'English'][rand(0, 4)],
                ]);
            }
        }

        // Create some parents if they don't exist
        $parentEmails = [
            'parent1@example.com',
            'parent2@example.com',
            'parent3@example.com',
        ];

        foreach ($parentEmails as $email) {
            if (!User::where('email', $email)->exists()) {
                User::create([
                    'name' => 'Parent ' . substr($email, 6, 1),
                    'email' => $email,
                    'password' => Hash::make('password'),
                    'role' => 'parent',
                    'email_verified_at' => now(),
                    'status' => 'active',
                    'balance' => rand(100000, 500000),
                    'phone' => '08' . rand(100000000, 999999999),
                    'address' => 'Jl. Example No. ' . rand(1, 100),
                ]);
            }
        }
    }
} 