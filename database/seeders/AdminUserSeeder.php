<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = 'admin@example.com';

        $user = User::where('email', $email)->first();

        if (! $user) {
            $user = User::create([
                'name' => 'Admin',
                'username' => 'admin',
                'email' => $email,
                'password' => Hash::make('password123'),
                'role' => 'admin',
            ]);
        } else {
            $user->role = 'admin';
            $user->password = Hash::make('password123');
            $user->save();
        }

        $this->command->info("Admin account ready: {$user->email} / password123");
    }
}
