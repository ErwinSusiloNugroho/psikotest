<?php
session_start();

// Include database configuration
require_once 'config.php';

// Fungsi untuk log aktivitas admin
function logAdminActivity($admin_id, $action, $details = null) {
    global $pdo;
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $user_agent = $_SERVER['HTTP_USER_AGENT'];
    
    $stmt = $pdo->prepare("INSERT INTO admin_logs (admin_id, action, details, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$admin_id, $action, $details, $ip_address, $user_agent]);
}

// Pastikan admin sudah login
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin-login.php');
    exit;
}

// Simpan data admin untuk logging
$admin_id = $_SESSION['admin_id'];
$admin_username = $_SESSION['admin_username'];
$was_remembered = isset($_SESSION['remembered']) ? $_SESSION['remembered'] : false;

// Log aktivitas logout
logAdminActivity($admin_id, 'LOGOUT', 'Admin logout dari IP: ' . $_SERVER['REMOTE_ADDR']);

// PERBAIKAN: Selalu hapus semua remember me token dan cookie
// Tidak peduli apakah sebelumnya menggunakan remember me atau tidak
if (isset($_COOKIE['admin_remember'])) {
    $token = $_COOKIE['admin_remember'];
    // Hapus token dari database
    $stmt = $pdo->prepare("DELETE FROM admin_remember_tokens WHERE token = ?");
    $stmt->execute([$token]);
    
    // Hapus cookie remember me
    setcookie('admin_remember', '', time() - 3600, '/');
}

// PERBAIKAN: Hapus semua token remember me untuk admin ini
// Untuk memastikan tidak ada token yang tersisa
$stmt = $pdo->prepare("DELETE FROM admin_remember_tokens WHERE admin_id = ?");
$stmt->execute([$admin_id]);

// Hapus semua data session
$_SESSION = array();

// Hapus session cookie jika ada
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// Destroy session
session_destroy();

// PERBAIKAN: Redirect ke halaman login dengan parameter logout dan no_remember
// Parameter no_remember akan mencegah auto-login dari remember me
header('Location: admin-login.php?logout=success&no_remember=1');
exit;
?>