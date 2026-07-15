<?php
require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Models\User::where('email', 'admin@example.com')->first();

if (! $user) {
    $user = new App\Models\User();
    $user->name = 'Admin';
    $user->username = 'admin';
    $user->email = 'admin@example.com';
    $user->password = Illuminate\Support\Facades\Hash::make('password123');
    $user->role = 'admin';
    $user->save();
} else {
    $user->role = 'admin';
    $user->save();
}

echo 'email=' . $user->email . PHP_EOL;
echo 'role=' . $user->role . PHP_EOL;
