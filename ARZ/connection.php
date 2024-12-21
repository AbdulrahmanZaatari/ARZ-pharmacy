<?php
// Define database credentials
$servername = "localhost";
$username = "root";
$password = "Abedamal123!";
$database = "arz_pharmacy_updated";

// Create a MySQLi connection
$conn = new mysqli($servername, $username, $password, $database);

// Check for connection errors
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set the character set to UTF-8 (optional but recommended for special characters)
$conn->set_charset("utf8");
?>
