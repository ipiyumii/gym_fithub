<?php
    define('DB_SERVER', 'localhost');  
    define('DB_USERNAME', 'root');     
    define('DB_PASSWORD', 'root');         
    define('DB_NAME', 'gym');    

    $admin_password = 'admin123';
    $admin_email = 'admin@fithub.com';

    // Attempt connection
    $conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
    if (!$conn) {
        die("ERROR: Could not connect to MySQL. " . mysqli_connect_error());
    }

    // Check if database exists; if not, create it
    $db_check = mysqli_select_db($conn, DB_NAME);
    if (!$db_check) {
        $sql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
        if (!mysqli_query($conn, $sql)) {
            die("ERROR: Could not create database. " . mysqli_error($conn));
        }
    }

    // Select the database 
    if (!mysqli_select_db($conn, DB_NAME)) {
        die("Database selection failed: " . mysqli_error($conn));
    }

    // // Create admin table if it doesn't exist
    // $sql = "CREATE TABLE IF NOT EXISTS admin (
    //     id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    //     full_name VARCHAR(100) NOT NULL,
    //     email VARCHAR(100) NOT NULL UNIQUE,
    //     password VARCHAR(255) NOT NULL,
    //     phone VARCHAR(20) NOT NULL,
    //     role ENUM('admin', 'user') DEFAULT 'user',
    //     created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    // )";

    // if (!mysqli_query($conn, $sql)) {
    //     die("ERROR: Could not create admin table. " . mysqli_error($conn));
    // }

    $check_admin = "SELECT id FROM admin WHERE email = '$admin_email' LIMIT 1";
    $result = mysqli_query($conn, $check_admin);

    if ($result && mysqli_num_rows($result) === 0) {
        // Insert default admin user
        $hashed_AdminPwd = password_hash($admin_password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO admin (full_name, email, password, phone, role) 
                VALUES ('Admin User', '$admin_email', '$hashed_AdminPwd', '1234567890', 'admin')";

        if (!mysqli_query($conn, $sql)) {
            if (mysqli_errno($conn) != 1062) { // Ignore duplicate email entry
                die("ERROR: Could not insert admin user. " . mysqli_error($conn));
            }
        }
    }
    
?>
