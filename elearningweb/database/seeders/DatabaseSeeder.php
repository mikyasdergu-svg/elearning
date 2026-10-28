<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'role' => 'admin',
            'teacher_status' => 'approved',
        ]);

        User::factory()->create([
            'name' => 'Pending Teacher',
            'email' => 'teacher.pending@example.com',
            'role' => 'teacher',
            'teacher_status' => 'pending',
        ]);

        User::factory()->create([
            'name' => 'Student User',
            'email' => 'student@example.com',
            'role' => 'student',
            'teacher_status' => 'approved',
        ]);
    }
}
