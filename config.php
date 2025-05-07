<?php
// Database Configuration
define('DB_SERVER', 'localhost');  // Database server
define('DB_USERNAME', 'root');     // Database username
define('DB_PASSWORD', '');         // Database password
define('DB_NAME', 'fithub_db');    // Database name

// Attempt to connect to MySQL database
$conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);

// Check connection
if (!$conn) {
    die("ERROR: Could not connect to MySQL. " . mysqli_connect_error());
}

// Check if database exists, if not create it
$db_check = mysqli_select_db($conn, DB_NAME);
if (!$db_check) {
    // Create database
    $sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
    if (mysqli_query($conn, $sql)) {
        // Select the database
        mysqli_select_db($conn, DB_NAME);
        
        // Run the database setup script if it exists
        if (file_exists('database.sql')) {
            $sql = file_get_contents('database.sql');
            if (mysqli_multi_query($conn, $sql)) {
                do {
                    // Store first result set
                    if ($result = mysqli_store_result($conn)) {
                        mysqli_free_result($result);
                    }
                } while (mysqli_more_results($conn) && mysqli_next_result($conn));
            }
        } else {
            // Create users table
            $sql = "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                full_name VARCHAR(100) NOT NULL,
                email VARCHAR(100) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                phone VARCHAR(20) NOT NULL,
                role ENUM('user', 'admin') DEFAULT 'user',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )";
            
            if (!mysqli_query($conn, $sql)) {
                die("ERROR: Could not create users table. " . mysqli_error($conn));
            }
            
            // Insert default admin user
            $admin_password = password_hash('admin123', PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (full_name, email, password, phone, role) 
                    VALUES ('Admin User', 'admin@fithub.com', '$admin_password', '1234567890', 'admin')";
            
            if (!mysqli_query($conn, $sql)) {
                // Ignore if admin already exists
                if (mysqli_errno($conn) != 1062) { // 1062 is error for duplicate entry
                    die("ERROR: Could not create admin user. " . mysqli_error($conn));
                }
            }
        }
    } else {
        die("ERROR: Could not create database. " . mysqli_error($conn));
    }
} else {
    // Database exists, just select it
    mysqli_select_db($conn, DB_NAME);
}
?>