<?php
require_once 'auth.php';
require_once 'config/db.php';

// ถ้าล็อกอินอยู่แล้ว ให้ redirect ไปหน้า skills.php ทันที
if (!empty($_SESSION['user_id'])) {
    header('Location: skills.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    check_auth_token();

    $email = strtolower(trim((string)($_POST['email'] ?? '')));
    $password = (string)($_POST['password'] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
        $error = 'Enter email and password.';
    } else {
        // TODO 1: Query a single user by email using a prepared statement.
        $stmt = mysqli_prepare($conn, "SELECT id, profile_id, email, password_hash FROM users WHERE email = ? LIMIT 1");

        if ($stmt) {
            // TODO 2: Use mysqli_stmt_bind_param / execute / bind_result / fetch.
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) === 1) {
                mysqli_stmt_bind_result($stmt, $db_id, $db_profile_id, $db_email, $db_password_hash);
                mysqli_stmt_fetch($stmt);

                // TODO 3: Verify plaintext password with password_verify($password, $hash).
                if (password_verify($password, $db_password_hash)) {
                    // TODO 4: On success, session_regenerate_id(true) and set session values:
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = $db_id;
                    $_SESSION['profile_id'] = $db_profile_id;
                    $_SESSION['email'] = $db_email;

                    // TODO 5: Redirect to skills.php and exit.
                    header('Location: skills.php');
                    exit;
                } else {
                    $error = 'Invalid email or password.';
                }
            } else {
                $error = 'Invalid email or password.';
            }
            mysqli_stmt_close($stmt);
        } else {
            $error = 'Database query failed.';
        }
    }
}
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <h2>เข้าสู่ระบบ (Login)</h2>

        <?php if (isset($_GET['registered'])): ?>
            <p class="ok">Registration complete. Please login.</p>
        <?php endif; ?>

        <?php if (isset($_GET['logged_out'])): ?>
            <p class="ok">Logged out successfully.</p>
        <?php endif; ?>

        <?php if ($error): ?>
            <p class="err"><?= auth_escape($error) ?></p>
        <?php endif; ?>

        <form method="post">
            <input type="hidden" name="auth_csrf" value="<?= auth_escape(auth_token()) ?>">
            
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required autocomplete="username" value="<?= isset($email) ? auth_escape($email) : '' ?>">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required autocomplete="current-password">
            </div>

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>