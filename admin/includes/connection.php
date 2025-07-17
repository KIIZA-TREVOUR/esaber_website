<?php
// Enable error reporting for debugging (remove in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
$host = 'localhost';
$username = 'root';         // Default XAMPP username
$password = '';             // Default XAMPP password (empty)
$database = 'esaber';       // Your database name

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to match your database
$conn->set_charset("utf8mb4");
?>
