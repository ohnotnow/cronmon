<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'username' => 'admin',
            'password' => bcrypt('secret'),
            'email' => 'admin@example.com',
            'is_admin' => true,
        ]);
    }
}
