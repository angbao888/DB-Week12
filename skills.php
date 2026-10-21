<?php
// ล็อกหน้านี้ทันทีด้วยการ require 'auth.php' ที่บรรทัดแรกสุด
require_once 'auth.php';
require_once 'config/db.php';

// ดึงข้อมูล Skills ทั้งหมดออกมาแสดงผล
$user_id = $_SESSION['user_id'] ?? null;
$profile_id = $_SESSION['profile_id'] ?? null;

$sql = "SELECT * FROM skill WHERE profile_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $profile_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>
<!doctype html>
<html lang="th">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>จัดการทักษะ (Skills Management)</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <header style="display: flex; justify-content: space-between; align-items: center;">
            <h2>รายการทักษะของคุณ (Skill List)</h2>
            <div>
                <span>ยินดีต้อนรับ: <?= auth_escape($_SESSION['email'] ?? '') ?></span> | 
                <a href="logout.php" onclick="return confirm('ยืนยันการออกจากระบบ?');">ออกจากระบบ (Logout)</a>
            </div>
        </header>

        <hr>

        <p><a href="skill_create.php" class="btn">+ เพิ่มทักษะใหม่</a></p>

        <table border="1" cellpadding="8" cellspacing="0" style="width: 100%; text-align: left;">
            <thead>
                <tr>
                    <th>ชื่อทักษะ (Skill Name)</th>
                    <th>ระดับความชำนาญ (Level)</th>
                    <th>จัดการ (Actions)</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= auth_escape($row['skill_name'] ?? $row['name'] ?? '') ?></td>
                            <td><?= auth_escape($row['level'] ?? $row['skill_level'] ?? '') ?></td>
                            <td>
                                <a href="skill_edit.php?id=<?= $row['id'] ?>">แก้ไข</a> | 
                                <a href="skill_delete.php?id=<?= $row['id'] ?>" onclick="return confirm('แน่ใจหรือไม่ว่าต้องการลบ?');">ลบ</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" style="text-align: center;">ยังไม่มีข้อมูลทักษะ</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>