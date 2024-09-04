<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    protected $model = Client::class;

    public function definition()
    {
        return [
            'surnom' => $this->faker->word,
            'telephone' => $this->faker->unique()->phoneNumber,
            'adresse' => $this->faker->address,
            'photo' => $this->faker->imageUrl(640, 480, 'people'),
            'user_id' => User::factory()->create()->id, // Associe le client à un utilisateur
        ];
    }
}

