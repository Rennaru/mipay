<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Atmin',
            'email' => 'admin@mipay.com',
            'password' => bcrypt('mimake'),
        ]);

        User::factory()->count(50)->create();
    }
}
