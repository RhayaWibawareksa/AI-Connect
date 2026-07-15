<?php
/**
 * Test MySQL Connection for AI-Connect
 */
$db_host = '127.0.0.1';
$db_port = 3306;
$db_name = 'db_ai_connect';
$db_user = 'root';
$db_pass = '';

try {
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);
    
    if ($conn->connect_error) {
        throw new Exception("Koneksi gagal: " . $conn->connect_error);
    }
    
    echo "✅ BERHASIL TERHUBUNG ke MySQL XAMPP!\n";
    echo "Host: $db_host:$db_port\n";
    echo "Database: $db_name\n";
    
    // Cek tabel
    $result = $conn->query("SHOW TABLES");
    echo "\n📊 Tabel yang ada:\n";
    while ($row = $result->fetch_row()) {
        echo "  • " . $row[0] . "\n";
    }
    
    // Cek data users
    $users = $conn->query("SELECT COUNT(*) as total FROM users");
    $user_count = $users->fetch_assoc()['total'];
    echo "\n👥 Total Users: $user_count\n";
    
    $conn->close();
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "\nPastikan:\n";
    echo "1. XAMPP MySQL sudah berjalan (Apache & MySQL)\n";
    echo "2. Database 'db_ai_connect' sudah exist\n";
    echo "3. Username/password benar di .env\n";
}
?>
