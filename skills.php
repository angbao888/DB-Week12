<?php
// 1. ล็อกหน้านี้ทันทีที่บรรทัดแรกสุด (ต้องล็อกอินก่อนเข้าดู)
require_once 'auth.php';

// 2. ดึงไฟล์เชื่อมต่อ DB และไฟล์ตกแต่งดีไซน์เดิมมาใช้
require_once 'config/db.php';
require_once 'crud_bootstrap.php'; // ดึงธีมเดิม

// 3. ดึง profile_id จากผู้ใช้ที่ล็อกอินอยู่จริง
$pid = $_SESSION['profile_id'] ?? null; 
$rows = [];

$stmt = mysqli_prepare($conn, 'SELECT id, skill_name, skill_level FROM skill WHERE profile_id = ? ORDER BY id DESC');
mysqli_stmt_bind_param($stmt, 'i', $pid);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $id, $name, $level);
while (mysqli_stmt_fetch($stmt)) {
    $rows[] = ['id' => $id, 'skill_name' => $name, 'skill_level' => $level];
}
mysqli_stmt_close($stmt);

// 4. แสดงผลโดยใช้ดีไซน์ธีมเดิมทั้งหมด
page_start('Manage Skills | CRUD Lab');

if (isset($_GET['success'])) {
    echo '<p class="ok">Action completed. Check the rows below.</p>';
}

// แถบแสดงชื่อผู้ใช้และปุ่ม Logout
echo '<div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">';
echo '<span>ยินดีต้อนรับ: ' . auth_escape($_SESSION['email'] ?? '') . '</span>';
echo '<a href="logout.php" onclick="return confirm(\'ยืนยันการออกจากระบบ?\');">ออกจากระบบ (Logout)</a>';
echo '</div>';

echo '<p class="muted">Existing resume skill records.</p>';
echo '<p><a href="skill_create.php" class="btn">+ เพิ่มทักษะใหม่</a></p>';

// ตารางแสดงข้อมูลดีไซน์เดิม
echo '<table><tr><th>ID</th><th>Skill</th><th>Level</th><th>Actions</th></tr>';
foreach ($rows as $r) {
    echo '<tr>';
    echo '<td>' . h($r['id']) . '</td>';
    echo '<td>' . h($r['skill_name']) . '</td>';
    echo '<td>' . h($r['skill_level']) . '</td>';
    echo '<td><a href="skill_edit.php?id=' . urlencode((string)$r['id']) . '">Edit</a> | <a href="skill_delete.php?id=' . urlencode((string)$r['id']) . '">Delete</a></td>';
    echo '</tr>';
}
echo '</table>';

page_end();
?>