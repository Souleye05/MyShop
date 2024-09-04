<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Dette>
 */
class DetteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $montant = $this->faker->randomFloat(2, 1, 1000);
        $montantDu = $this->faker->randomFloat(2, 1, $montant);
        $montantRestant = $this->faker->randomFloat(2, 1, $montantDu);
    
        return [
            'client_id' => \App\Models\Client::factory(),
            'date' => $this->faker->date(),
            'montant' => $montant,
            'montantDu' => $montantDu,
            'montantRestant' => $montantRestant
        ];
    }
    
}
