<?php
require_once 'config/db.php';

$email = 'demo@example.com';
$password = 'password123';
$hash = password_hash($password, PASSWORD_BCRYPT);

$stmt = mysqli_prepare($conn, "UPDATE users SET password_hash = ? WHERE id = 1 OR email = ?");
mysqli_stmt_bind_param($stmt, "ss", $hash, $email);

if (mysqli_stmt_execute($stmt)) {
    echo "SUCCESS: Password hash updated to: " . $hash;
} else {
    echo "ERROR: " . mysqli_error($conn);
}
?>