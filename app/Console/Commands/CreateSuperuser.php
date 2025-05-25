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
    protected $signature = 'rt:superuser {username} {email} {password}';

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
        $username = $this->argument('username');
        $password = $this->argument('password');
        $email    = $this->argument('email');

        if (User::whereUsername($username)->exists())
        {
            $this->info("User {$username} already exists.");
        }
        else
        {
            User::create([
                'username' => $username,
                'password' => Hash::make($password),
                'email'    => $email,
            ]);
            $this->info("Created superuser {$username}.");
        }

        return 0;
    }
}
