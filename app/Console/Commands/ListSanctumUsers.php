<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class ListSanctumUsers extends Command
{
    protected $signature = 'sanctum:list-users';

    protected $description = 'List users and their Sanctum token metadata';

    public function handle(): int
    {
        $users = User::query()
            ->with('tokens')
            ->orderBy('id')
            ->get();

        if ($users->isEmpty()) {
            $this->info('No users found.');

            return self::SUCCESS;
        }

        $rows = [];

        foreach ($users as $user) {
            if ($user->tokens->isEmpty()) {
                $rows[] = [$user->id, $user->name, $user->email, '-', '-', '-'];

                continue;
            }

            foreach ($user->tokens as $token) {
                $rows[] = [
                    $user->id,
                    $user->name,
                    $user->email,
                    $token->id,
                    $token->name,
                    $token->last_used_at?->toDateTimeString() ?? 'Never',
                ];
            }
        }

        $this->table(
            ['User ID', 'Name', 'Email', 'Token ID', 'Token Name', 'Last Used'],
            $rows,
        );

        $this->line('Plaintext token values cannot be recovered from Sanctum.');

        return self::SUCCESS;
    }
}
