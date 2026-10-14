<?php
$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 's68160060';
$pass = getenv('DB_PASS') ?: 'f2SUTPaT';
$dbname = "s68160060";

$conn = mysqli_connect($host, $user, $pass, $dbname);
if (!$conn) { 
    error_log(mysqli_connect_error()); 
    http_response_code(500); 
    exit('Database Connection Error');
}
mysqli_set_charset($conn, 'utf8mb4');