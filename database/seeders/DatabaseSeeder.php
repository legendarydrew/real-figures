<?php

namespace Database\Seeders;

use App\Models\Song;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'name'     => 'Test User',
            'email'    => 'holla@silentmode.tv',
            'password' => Hash::make('temp'),
        ]);

        Song::factory(20)->withAct()->withUrl(80)->create();
    }
}
