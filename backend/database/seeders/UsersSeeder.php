<?php

namespace Database\Seeders;

use App\Models\users;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testUsers = [
            [
                'nama' => 'John Doe',
                'username' => 'john.doe',
                'password' => Hash::make('password123'),
                'role' => 'users',
            ],
            [
                'nama' => 'Jane Smith',
                'username' => 'jane.smith',
                'password' => Hash::make('password123'),
                'role' => 'users',
            ],
            [
                'nama' => 'Mike Johnson',
                'username' => 'mike.johnson',
                'password' => Hash::make('password123'),
                'role' => 'users',
            ],
            [
                'nama' => 'Sarah Williams',
                'username' => 'sarah.williams',
                'password' => Hash::make('password123'),
                'role' => 'users',
            ],
            [
                'nama' => 'Admin User',
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ],
        ];

        foreach ($testUsers as $user) {
            users::create($user);
        }
    }
}
