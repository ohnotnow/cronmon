<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\User;
use App\Template;
use Faker\Generator as Faker;

$factory->define(Template::class, function (Faker $faker) {
    return [
        'name' => $faker->text(30),
        'slug' => $faker->slug,
        'user_id' => function () {
            return factory(User::class)->create()->id;
        },
        'uuid' => $faker->uuid,
        'grace' => 5,
        'grace_units' => 'minute',
        'period' => 1,
        'period_units' => 'hour',
        'email' => '',
        'team_id' => null,
    ];
});
