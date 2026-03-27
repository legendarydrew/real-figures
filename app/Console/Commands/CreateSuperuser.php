<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

/**
 * Class CreateSuperuser
 */
#[Signature('rt:superuser {email} {password}')]
#[Description('Create or update a superuser.')]
class CreateSuperuser extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        if (User::whereEmail($email)->exists()) {
            $this->info("User for {$email} already exists.");
        } else {
            User::factory()->create([
                'name' => 'SilentMode',
                'email' => $email,
                'password' => Hash::make($password),
            ]);
            $this->info("Created superuser for {$email}.");
        }

        return 0;
    }
}
