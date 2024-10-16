<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        // if there is no data call the seeders 
        // if database is created do not run the seeders
        if (Schema::hasTable('users') && DB::table('users')->count() == 0) {
            // If the database is not created, run the seeders
            $this->call(AuthorSeeder::class);
            $this->call(BookSeeder::class);
            $this->call(UserSeeder::class);
        }
    }
}
