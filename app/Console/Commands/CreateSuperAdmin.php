<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;

class CreateSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crm:create-super-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new super-administrator user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Creating a new super-admin user...');

        // Ensure the 'Admin' role exists, creating it if necessary.
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'web']);

        $name = $this->ask('Enter the name for the admin user');
        $email = $this->ask('Enter the email for the admin user');
        $password = $this->secret('Enter a password for the admin user (min. 8 characters)');

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            $this->error('User creation failed. Please fix the following errors:');
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return 1; // Error exit code
        }

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $user->assignRole($adminRole);

        $this->info('Super-admin user created successfully!');
        $this->line("You can now log in with the email <comment>{$user->email}</comment> and the password you provided.");

        return 0; // Success exit code
    }
}
