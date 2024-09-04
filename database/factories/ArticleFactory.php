<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Article>
 */
class ArticleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'libelle' => $this->faker->paragraph(3),
            'prix' => $this->faker->randomFloat(2, 1, 100),
            'qteStock' => $this->faker->numberBetween(1, 100),
        ];
    }
}
