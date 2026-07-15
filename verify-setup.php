<?php
/**
 * VERIFICATION SCRIPT - Setup Checker
 * 
 * Jalankan: php verify-setup.php
 * 
 * Script ini mengecek apakah semua komponen sudah siap untuk:
 * - MySQL Connection
 * - Laravel Socialite Installation
 * - Google OAuth Configuration
 * - Required Files
 */

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║       AI-CONNECT SETUP VERIFICATION CHECKER                ║\n";
echo "║                                                            ║\n";
echo "║  Checking: MySQL, Socialite, Google OAuth, Required Files ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

$checks_passed = 0;
$checks_total = 0;

// === CHECK 1: .env File ===
echo "1️⃣  Checking .env configuration...\n";
$checks_total++;
if (file_exists('.env')) {
    $env_content = file_get_contents('.env');
    
    // Check MySQL config
    if (preg_match('/DB_CONNECTION=mysql/', $env_content) &&
        preg_match('/DB_HOST=127.0.0.1/', $env_content) &&
        preg_match('/DB_DATABASE=db_ai_connect/', $env_content)) {
        echo "   ✅ MySQL configuration found\n";
        $checks_passed++;
    } else {
        echo "   ❌ MySQL configuration missing or incomplete\n";
    }
    
    // Check Google OAuth config
    if (preg_match('/GOOGLE_CLIENT_ID=/', $env_content) && 
        preg_match('/GOOGLE_CLIENT_SECRET=/', $env_content)) {
        if (preg_match('/GOOGLE_CLIENT_ID=your_/', $env_content)) {
            echo "   ⚠️  Google OAuth credentials placeholder (need to update)\n";
        } else {
            echo "   ✅ Google OAuth credentials configured\n";
            $checks_passed++;
        }
    } else {
        echo "   ⚠️  Google OAuth credentials not found (will need to add)\n";
    }
} else {
    echo "   ❌ .env file not found!\n";
}
echo "\n";

// === CHECK 2: composer.json ===
echo "2️⃣  Checking composer dependencies...\n";
$checks_total++;
if (file_exists('composer.json')) {
    $composer = json_decode(file_get_contents('composer.json'), true);
    
    if (isset($composer['require']['laravel/framework'])) {
        echo "   ✅ Laravel framework installed\n";
    }
    
    if (isset($composer['require']['laravel/sanctum'])) {
        echo "   ✅ Laravel Sanctum installed\n";
        $checks_passed++;
    } else {
        echo "   ⚠️  Laravel Sanctum not in composer.json\n";
    }
    
    // Check if Socialite might be installed
    if (file_exists('vendor/laravel/socialite')) {
        echo "   ✅ Laravel Socialite installed\n";
        $checks_passed++;
    } else {
        echo "   ⚠️  Laravel Socialite not found (run: composer require laravel/socialite)\n";
    }
} else {
    echo "   ❌ composer.json not found!\n";
}
echo "\n";

// === CHECK 3: Required Controllers ===
echo "3️⃣  Checking required controller files...\n";
$checks_total++;
$required_files = [
    'app/Http/Controllers/GoogleAuthController.php' => 'Google OAuth Controller',
    'app/Http/Controllers/PostViewController.php' => 'Post View Controller',
];

foreach ($required_files as $file => $name) {
    if (file_exists($file)) {
        echo "   ✅ $name found\n";
        $checks_passed++;
    } else {
        echo "   ❌ $name not found at: $file\n";
    }
}
echo "\n";

// === CHECK 4: Database Configuration ===
echo "4️⃣  Checking database configuration...\n";
$checks_total++;
if (file_exists('config/database.php')) {
    echo "   ✅ Database config file exists\n";
    $checks_passed++;
}
echo "\n";

// === CHECK 5: Migration Files ===
echo "5️⃣  Checking migration files...\n";
$checks_total++;
$migrations = glob('database/migrations/*');
if (count($migrations) > 0) {
    echo "   ✅ Found " . count($migrations) . " migration files\n";
    
    $google_migration = array_filter($migrations, function($file) {
        return strpos($file, 'add_google_auth_to_users') !== false;
    });
    
    if (!empty($google_migration)) {
        echo "   ✅ Google OAuth migration found\n";
        $checks_passed++;
    } else {
        echo "   ⚠️  Google OAuth migration not found yet (will be created)\n";
    }
} else {
    echo "   ❌ No migration files found!\n";
}
echo "\n";

// === CHECK 6: Views ===
echo "6️⃣  Checking view files...\n";
$checks_total++;
$required_views = [
    'resources/views/auth/login.blade.php' => 'Login view',
    'resources/views/dashboard.blade.php' => 'Dashboard view',
];

foreach ($required_views as $file => $name) {
    if (file_exists($file)) {
        echo "   ✅ $name exists\n";
        $checks_passed++;
    } else {
        echo "   ❌ $name not found: $file\n";
    }
}
echo "\n";

// === CHECK 7: Models ===
echo "7️⃣  Checking model files...\n";
$checks_total++;
if (file_exists('app/Models/User.php')) {
    $user_model = file_get_contents('app/Models/User.php');
    if (strpos($user_model, 'google_id') !== false) {
        echo "   ✅ User model has Google OAuth fields\n";
        $checks_passed++;
    } else {
        echo "   ⚠️  User model may need Google OAuth field additions\n";
    }
}
echo "\n";

// === CHECK 8: Routes ===
echo "8️⃣  Checking routes...\n";
$checks_total++;
if (file_exists('routes/web.php')) {
    $routes = file_get_contents('routes/web.php');
    if (strpos($routes, 'GoogleAuthController') !== false) {
        echo "   ✅ GoogleAuthController imported in routes\n";
    }
    if (preg_match('/auth\.google/', $routes)) {
        echo "   ✅ Google OAuth routes found\n";
        $checks_passed++;
    } else {
        echo "   ⚠️  Google OAuth routes not found yet\n";
    }
}
echo "\n";

// === CHECK 9: Config/Services ===
echo "9️⃣  Checking services config...\n";
$checks_total++;
if (file_exists('config/services.php')) {
    $services = file_get_contents('config/services.php');
    if (strpos($services, "'google'") !== false) {
        echo "   ✅ Google service configuration found\n";
        $checks_passed++;
    } else {
        echo "   ⚠️  Google service configuration not found\n";
    }
}
echo "\n";

// === SUMMARY ===
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║                     VERIFICATION SUMMARY                   ║\n";
echo "╠════════════════════════════════════════════════════════════╣\n";
echo "║ Total Checks: $checks_total                                     ║\n";
echo "║ Passed: $checks_passed                                          ║\n";

if ($checks_passed >= 8) {
    echo "║ Status: ✅ READY TO SETUP!                                   ║\n";
} elseif ($checks_passed >= 5) {
    echo "║ Status: ⚠️  MOSTLY READY (some items need attention)         ║\n";
} else {
    echo "║ Status: ❌ NEEDS MORE SETUP                                  ║\n";
}

echo "╚════════════════════════════════════════════════════════════╝\n\n";

// === NEXT STEPS ===
echo "📋 NEXT STEPS:\n";
echo "1. Follow QUICK_SETUP.md for step-by-step instructions\n";
echo "2. If Socialite not found, run: composer require laravel/socialite\n";
echo "3. Get Google OAuth credentials from: https://console.cloud.google.com/\n";
echo "4. Update .env with Google CLIENT_ID and CLIENT_SECRET\n";
echo "5. Run: php artisan migrate\n";
echo "6. Run: php artisan serve\n";
echo "7. Test at: http://localhost:8000/login\n\n";

?>
