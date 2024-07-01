<?php
// Replace with your actual database credentials
$host = 'localhost'; // or your host
$dbname = 'user_records';
$username = 'root';
$password = '';

// Establish a database connection using PDO
try {
    $db = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable error reporting
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
