<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminUser;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        AdminUser::create([
            'id' => 1,
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('admin123'),
            'role' => 'super_admin',
        ]);

        AdminUser::create([
            'id' => 2,
            'name' => 'Editor',
            'email' => 'editor@example.com',
            'password' => bcrypt('editor123'),
            'role' => 'editor',
        ]);
    }
}