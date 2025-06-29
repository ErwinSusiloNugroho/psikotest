<?php
// Database configuration
$host = 'localhost';
$dbname = 'tes';
$username = 'root';
$password = '';
->exec($createTableSQL);
    
catch(PDOException $e) {
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