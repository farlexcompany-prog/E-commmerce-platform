<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{
    protected $signature = 'admin:create {username?}';

    protected $description = 'Create an administrator account for the order dashboard';

    public function handle(): int
    {
        $username = strtolower(trim((string) ($this->argument('username') ?? $this->ask('Admin username'))));

        $validator = Validator::make(
            ['username' => $username],
            ['username' => ['required', 'string', 'min:3', 'max:40', 'regex:/^[a-z0-9][a-z0-9._-]*$/', 'unique:admins,username']],
        );

        if ($validator->fails()) {
            $this->error($validator->errors()->first());

            return self::FAILURE;
        }

        $password = $this->secret('Password (at least 12 characters)');
        if (! is_string($password) || strlen($password) < 12) {
            $this->error('The password must contain at least 12 characters.');

            return self::FAILURE;
        }

        if ($password !== $this->secret('Confirm password')) {
            $this->error('The passwords did not match.');

            return self::FAILURE;
        }

        $admin = new Admin;
        $admin->username = $username;
        $admin->password = Hash::make($password);
        $admin->save();

        $this->info('Admin account created. Sign in at /admin/login.');

        return self::SUCCESS;
    }
}
