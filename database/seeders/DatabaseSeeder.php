<?php

namespace Database\Seeders;

use App\Models\Act;
use App\Models\ContactMessage;
use App\Models\Donation;
use App\Models\Stage;
use App\Models\Subscriber;
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

        $this->call(LanguagesSeeder::class);

        // Test data for non-live environments.
        if (!app()->isProduction())
        {
            $this->command->comment('Creating test user...');
            User::factory()->create([
                'name'     => 'Test User',
                'email'    => 'holla@silentmode.tv',
                'password' => Hash::make('temp'),
            ]);

            $this->command->comment('Creating dummy Acts...');
            Act::factory(4)->withPicture(50)->withProfile()->withMeta()->withSong()->create();
            Act::factory(4)->withPicture(50)->withProfile()->withSong()->create();
            Act::factory(4)->withPicture(50)->withProfile()->create();
            Act::factory(4)->withPicture(50)->withSong()->create();
            Act::factory(4)->withPicture(50)->create();

            $this->command->comment('Creating dummy Stages...');
            Stage::factory()->withResults()->create();

            $this->command->comment('Creating dummy Donations...');
            Donation::factory(fake()->numberBetween(1, 20))->create();

            $this->command->comment('Creating dummy Subscribers...');
            Subscriber::factory(fake()->numberBetween(1, 20))->create();

            $this->command->comment('Creating dummy contact messages...');
            ContactMessage::factory(fake()->numberBetween(1, 40))->create();
        }
    }
}
