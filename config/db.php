<?php
// Simple procedural MySQL connection using mysqli
$host = 'localhost';
$user = 's68160060';
$pass = 'f2SUTPaT';
$dbname = 's68160060';

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die('Database connection failed: ' . mysqli_connect_error());
}

mysqli_set_charset($conn, 'utf8mb4');
?>
