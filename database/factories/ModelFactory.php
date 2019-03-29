<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use Illuminate\Support\Str;
use App\CronUuid;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'username' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => Str::random(10),
        'is_admin' => false,
    ];
});

$factory->define(\App\Cronjob::class, function (Faker\Generator $faker) {

    return [
        'email' => $faker->unique()->safeEmail,
        'name' => $faker->word(),
        'grace' => 5,
        'grace_units' => 'minute',
        'period' => 1,
        'period_units' => 'hour',
        'user_id' => factory(\App\User::class)->create()->id,
        'email' => 'test@test.com',
        'last_run' => null,
        'is_silenced' => false,
        'uuid' => CronUuid::generate(),
    ];
});

$factory->define(\App\Team::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
    ];
});

$factory->define(\App\Ping::class, function (Faker\Generator $faker) {
    return [
        'cronjob_id' => factory(\App\Cronjob::class)->create()->id,
    ];
});
