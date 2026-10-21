<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$timeout = 600; 

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
    $_SESSION = array();
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    session_destroy();
    header('Location: login.php?timeout=1');
    exit;
}

$_SESSION['last_activity'] = time();

function check_auth() {
    if (empty($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}

function auth_token() {
    if (empty($_SESSION['auth_csrf_token'])) {
        $_SESSION['auth_csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['auth_csrf_token'];
}

function check_auth_token() {
    $token = $_POST['auth_csrf'] ?? '';
    if (empty($token) || !hash_equals($_SESSION['auth_csrf_token'] ?? '', $token)) {
        http_response_code(403);
        die('CSRF token validation failed.');
    }
}

function auth_escape($string) {
    return htmlspecialchars((string)$string, ENT_QUOTES, 'UTF-8');
}

$current_page = basename($_SERVER['PHP_SELF']);
if (!in_array($current_page, ['login.php', 'register.php', 'make_pass.php'])) {
    check_auth();
}
?>