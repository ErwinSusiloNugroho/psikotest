<?php
// config.php - Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'tes');
define('DB_USER', 'root'); // Ganti dengan username database Anda
define('DB_PASS', ''); // Ganti dengan password database Anda

// Membuat koneksi ke database
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    // Set timezone
    $pdo->exec("SET time_zone = '+07:00'");
} catch(PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}

// Fungsi untuk membersihkan input
function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Fungsi untuk generate session token
function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}
?>