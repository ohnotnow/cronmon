<?php

namespace Database\Factories;

use App\Cronjob;
use App\CronUuid;
use Illuminate\Database\Eloquent\Factories\Factory;

class CronjobFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Cronjob::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email' => $this->faker->unique()->safeEmail,
            'name' => $this->faker->word(),
            'grace' => 5,
            'grace_units' => 'minute',
            'period' => 1,
            'period_units' => 'hour',
            'user_id' => \App\User::factory(),
            'email' => 'test@test.com',
            'last_run' => null,
            'is_silenced' => false,
            'uuid' => CronUuid::generate(),
        ];
    }
}
