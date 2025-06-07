<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$dbName = 'school_db';

// 1. Connect to MySQL server
$conn = new mysqli($host, $user, $pass);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 2. Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS $dbName";
if (!$conn->query($sql)) {
    die("Database creation failed: " . $conn->error);
}

// 3. Select the database with error checking
if (!$conn->select_db($dbName)) {
    die("Database selection failed: " . $conn->error);
}

// 4. Set character encoding
if (!$conn->set_charset("utf8mb4")) {
    die("Error setting charset: " . $conn->error);
}

// 5. Create students table if it doesn't exist
$tableSql = "
CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    age INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

if (!$conn->query($tableSql)) {
    die("Table creation failed: " . $conn->error);
}

// Return connection for use in other files
return $conn;
?>