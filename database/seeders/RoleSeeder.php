<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::factory()->create(['libelle' => 'Admin']);
        Role::factory()->create(['libelle' => 'Boutiquier']);
        Role::factory()->create(['libelle' => 'Client']);
        Role::factory()->create(['libelle' => 'User']);

    }
    
}
