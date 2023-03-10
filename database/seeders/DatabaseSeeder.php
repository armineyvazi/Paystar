<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\UserSeeder::factory(10)->create();

        // \App\Models\UserSeeder::factory()->create([
        //     'name' => 'Test UserSeeder',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            ProductSeeder::class,
            UserSeeder::class,
        ]);
    }
}
