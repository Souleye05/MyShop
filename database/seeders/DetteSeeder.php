<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DetteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        foreach (range(1, 50) as $index) {
            DB::table('dettes')->insert([
                'client_id' => \App\Models\Client::inRandomOrder()->first()->id, // Choisir un client aléatoire
                'date' => $faker->date(),
                'montant' => $faker->randomFloat(2, 1, 1000),
                'montantDu' => $faker->randomFloat(2, 1, $faker->randomFloat(2, 1, 1000)),
                'montantRestant' => $faker->randomFloat(2, 1, $faker->randomFloat(2, 1, 1000))
            ]);
        }
    }
}
