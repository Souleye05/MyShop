<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Le nom du modèle associé à cette factory.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Définir l'état par défaut du modèle.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'password' => bcrypt('password'), // mot de passe par défaut haché
            'login' => $this->faker->unique()->userName,
            'photo' => $this->faker->imageUrl(640, 480),
            'status' => $this->faker->boolean(80), // 80% de chances de retourner true (actif)
            'role_id' => \App\Models\Role::factory(), // Associe un rôle créé par une autre factory
        ];
    }
}
