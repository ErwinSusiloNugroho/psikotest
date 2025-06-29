<?php
// Database configuration
$host = 'localhost';
$dbname = 'tes';
$username = 'root';
$password = '';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    
    // Set error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Example table creation SQL (you can modify this based on your needs)
    $createTableSQL = "CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";
    
    // Execute the table creation
    $pdo->exec($createTableSQL);
    
    
} catch(PDOException $e) {
    // Display user-friendly error message
    die("
    <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 50px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;'>
        <h2 style='color: #d32f2f; margin-bottom: 20px;'>Database Connection Error</h2>
        <p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>
        <h3>Troubleshooting Steps:</h3>
        <ol>
            <li>Make sure XAMPP/WAMP/MAMP is running</li>
            <li>Check if MySQL service is started</li>
            <li>Verify database credentials in config.php</li>
            <li>Ensure database 'tes' exists in MySQL</li>
            <li>Try accessing phpMyAdmin at <a href='http://localhost/phpmyadmin'>http://localhost/phpmyadmin</a></li>
        </ol>
        <p><em>If the problem persists, please check your local server configuration.</em></p>
    </div>
    ");
}
?>