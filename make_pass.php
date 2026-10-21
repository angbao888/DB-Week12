<?php
require_once 'config/db.php';

$email = '68160060@go.buu.ac.th';
$password = 'password888';
$hash = password_hash($password, PASSWORD_BCRYPT);

$stmt = mysqli_prepare($conn, "UPDATE users SET email = ?, password_hash = ? WHERE id = 1 OR profile_id = 1");
mysqli_stmt_bind_param($stmt, "ss", $email, $hash);

if (mysqli_stmt_execute($stmt)) {
    echo "SUCCESS: Email and password hash updated to: " . $email;
} else {
    echo "ERROR: " . mysqli_error($conn);
}
?>