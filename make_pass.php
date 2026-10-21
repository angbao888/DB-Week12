<?php
require_once 'config/db.php';

$email = '68160060@go.buu.ac.th';
$password = 'password888';
$hash = password_hash($password, PASSWORD_BCRYPT);

$stmt = mysqli_prepare($conn, "UPDATE users SET password_hash = ? WHERE email = ?");
mysqli_stmt_bind_param($stmt, "ss", $hash, $email);

if (mysqli_stmt_execute($stmt)) {
    echo "SUCCESS: Password hash updated to: " . $hash;
} else {
    echo "ERROR: " . mysqli_error($conn);
}
?>