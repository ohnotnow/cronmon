<?php

namespace Database\Factories;

use App\Template;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TemplateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Template::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->text(30),
            'slug' => $this->faker->slug,
            'user_id' => User::factory(),
            'uuid' => $this->faker->uuid,
            'grace' => 5,
            'grace_units' => 'minute',
            'period' => 1,
            'period_units' => 'hour',
            'email' => '',
            'team_id' => null,
        ];
    }
}
