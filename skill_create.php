<?php
require 'crud_bootstrap.php';
$error = ''; $name = ''; $level = 'Beginner';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim((string)($_POST['skill_name'] ?? ''));
    $level = trim((string)($_POST['skill_level'] ?? ''));
    $pid = profile_id($conn);

    if ($pid === 0 || $name === '' || mb_strlen($name) > 120 || !in_array($level, ['Beginner', 'Intermediate', 'Advanced'], true)) {
        $error = 'ข้อมูลไม่ถูกต้อง';
    } else {
        // TODO 1: Fill SQL INSERT. Keep placeholders, never concatenate input values.
        $sql = "INSERT INTO skill (profile_id, skill_name, skill_level) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            $error = 'SQL not ready: review TODO 1.';
        } else {
            mysqli_stmt_bind_param($stmt, 'iss', $pid, $name, $level);
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_close($stmt);
                header('Location: skills.php?msg=added');
                exit;
            }
            $error = 'บันทึกข้อมูลไม่สำเร็จ';
            mysqli_stmt_close($stmt);
        }
    }
}

page_start('CREATE | Add Skill');
if ($error) echo '<p class="err">' . h($error) . '</p>';
?>

<form method="post">
    <?php csrf_field(); ?>
    <label>Skill Name</label>
    <input name="skill_name" required maxlength="120" value="<?= h($name) ?>">
    <label>Skill Level</label>
    <select name="skill_level">
        <?php foreach (['Beginner', 'Intermediate', 'Advanced'] as $choice): ?>
            <option value="<?= h($choice) ?>" <?= $choice === $level ? 'selected' : '' ?>><?= h($choice) ?></option>
        <?php endforeach; ?>
    </select>
    <p><button type="submit">Save Skill</button></p>
</form>

<?php page_end(); ?>