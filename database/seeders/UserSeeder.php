<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        $adminRole = Role::where('libelle', 'Admin')->first();
        $userRole = Role::where('libelle', 'User')->first();

        User::factory()->create([
            'name' => 'Admin',
            'last_name' => 'User',
            'login' => 'admin',
            'password' => bcrypt('password'),
            'role_id' => $adminRole->id,
            'status' => true,
        ]);

        User::factory()->create([
            'name' => 'John',
            'last_name' => 'Doe',
            'login' => 'johndoe',
            'password' => bcrypt('password'),
            'role_id' => $userRole->id,
            'status' => true,
        ]);
    }
}
