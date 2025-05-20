<?php

namespace Database\Seeders;

use App\Models\Act;
use App\Models\Song;
use App\Models\Stage;
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

        Act::factory(5)->withPicture(50)->withProfile()->withSong()->create();
        Act::factory(5)->withPicture(50)->withProfile()->create();
        Act::factory(5)->withPicture(50)->withSong()->create();
        Act::factory(5)->withPicture(50)->create();

        Stage::factory()->withResults()->create();
    }
}
