<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RegenerateSanctumToken extends Command
{
    protected $signature = 'sanctum:regenerate-token {email : The email address of the user}';

    protected $description = 'Revoke a user\'s Sanctum tokens and generate a new one';

    public function handle(): int
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if ($user === null) {
            $this->error("No user found with email [{$email}].");

            return self::FAILURE;
        }

        $token = DB::transaction(function () use ($user) {
            $user->tokens()->delete();

            return $user->createToken('api-token')->plainTextToken;
        });

        $this->info("All existing Sanctum tokens revoked for {$user->email}.");
        $this->info('New Sanctum token: '.$token);

        return self::SUCCESS;
    }
}
