<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class CreateSanctumUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sanctum:create-user {name} {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a user and generate a Sanctum token';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::create([
            'name' => $this->argument('name'),
            'email' => $this->argument('email'),
            'password' => bcrypt($this->argument('password')),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        $this->info('User created: ' . $user->email);
        $this->info('Sanctum token: ' . $token);
    }
}
