<?php
// Database configuration
$host = 'localhost';
$dbname = 'psikotest_burnout';
$username = 'root';
$password = '';

try {
    // First, connect without database to create it if needed
    $pdo_temp = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
    $pdo_temp->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    $pdo_temp->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    
    // Now connect to the specific database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Close temporary connection
    $pdo_temp = null;
    
    // Create tables if they don't exist
    $createTableSQL = "CREATE TABLE IF NOT EXISTS user_identitas (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nama VARCHAR(255) NOT NULL,
        jenis_kelamin ENUM('Laki-laki', 'Perempuan') NOT NULL,
        usia INT NOT NULL,
        pekerjaan VARCHAR(255) NOT NULL,
        pendidikan ENUM('SD','SMP','SMA','D3','S1','S2','S3') NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $pdo->exec($createTableSQL);
    
} catch(PDOException $e) {
    // Display user-friendly error message
    die("
    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;'>
        <h2 style='color: #d32f2f; margin-bottom: 20px;'>Database Connection Error</h2>
        <p><strong>Error:</strong> " . $e->getMessage() . "</p>
        <h3>Troubleshooting Steps:</h3>
        <ol>
            <li>Make sure XAMPP/WAMP/MAMP is running</li>
            <li>Check if MySQL service is started</li>
            <li>Verify database credentials in config.php</li>
            <li>Try accessing phpMyAdmin at <a href='http://localhost/phpmyadmin'>http://localhost/phpmyadmin</a></li>
        </ol>
        <p><em>If the problem persists, please check your local server configuration.</em></p>
    </div>
    ");
}
?>