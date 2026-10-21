<?php
// 1. เริ่มต้น Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. เคลียร์ตัวแปรใน Session ทั้งหมดให้เป็นอาร์เรย์ว่าง
$_SESSION = array();

// 3. ทำลาย Session Cookie ฝั่ง Web Browser (ถ้ามี)
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. ทำลาย Session บนเซิร์ฟเวอร์
session_destroy();

// 5. เด้งกลับไปหน้า login.php พร้อมส่ง query string บล็อกแจ้งเตือน
header('Location: login.php?logged_out=1');
exit;
?>