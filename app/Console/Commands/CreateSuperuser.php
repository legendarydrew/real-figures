<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

/**
 * Class CreateSuperuser
 */
class CreateSuperuser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rt:superuser {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create or update a superuser.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        if (User::whereEmail($email)->exists())
        {
            $this->info("User for {$email} already exists.");
        }
        else
        {
            User::factory()->create([
                'email' => $email,
                'password' => Hash::make($password),
            ]);
            $this->info("Created superuser for {$email}.");
        }

        return 0;
    }
}
