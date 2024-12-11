<?php
// Define database connection parameters
$host = 'localhost';    // Host name
$dbname = 'biomatric'; // Database name
$username = 'root'; // MySQL username
$password = ''; // MySQL password

// Create connection
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
// echo "Connected successfully";
?>
