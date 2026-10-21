<?php
// เริ่มต้นใช้งาน Session หากยังไม่มีการ start
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * ฟังก์ชันตรวจสอบสถานะการเข้าสู่ระบบ (Authentication Check)
 * หากผู้ใช้ยังไม่ได้ล็อกอิน ให้เปลี่ยนหน้าไปยัง login.php ทันที
 */
function check_auth() {
    if (empty($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
}

/**
 * ฟังก์ชันสร้างและรับ Token ป้องกัน CSRF Attack
 */
function auth_token() {
    if (empty($_SESSION['auth_csrf_token'])) {
        $_SESSION['auth_csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['auth_csrf_token'];
}

/**
 * ฟังก์ชันตรวจสอบ Token CSRF จากแบบฟอร์ม POST
 */
function check_auth_token() {
    $token = $_POST['auth_csrf'] ?? '';
    if (empty($token) || !hash_equals($_SESSION['auth_csrf_token'] ?? '', $token)) {
        http_response_code(403);
        die('CSRF token validation failed.');
    }
}

/**
 * ฟังก์ชัน Escape ข้อความสำหรับแสดงผลปลอดภัย ป้องกัน XSS
 */
function auth_escape($string) {
    return htmlspecialchars((string)$string, ENT_QUOTES, 'UTF-8');
}

// ตรวจสอบการล็อกอินอัตโนมัติเมื่อไฟล์นี้ถูกเรียกใช้ (ยกเว้นในหน้า login.php หรือ register.php)
$current_page = basename($_SERVER['PHP_SELF']);
if (!in_array($current_page, ['login.php', 'register.php'])) {
    check_auth();
}
?>