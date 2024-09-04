<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    public function run()
    {
        // Créez 10 clients avec des données factices
        Client::factory(10)->create();
    }
}

