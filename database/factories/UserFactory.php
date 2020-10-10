<?php

namespace Database\Factories;

use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'username' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => '$2y$10$OGsEr5fHNbvU2Tlr4VvvZ.8HuZP02Tt78SiGwwzul7w9.I50ewQhy', // secret
            'remember_token' => Str::random(10),
            'is_admin' => false,
        ];
    }
}
