<?php
$ADMIN_USER = 'admin';
$ADMIN_PASS = 'amin9236hn';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_POST['user'] === $ADMIN_USER && $_POST['pass'] === $ADMIN_PASS) {
        $_SESSION['auth'] = true;
    } else {
        $error = 'نام کاربری یا رمز اشتباه است';
    }
}

if (!isset($_SESSION['auth'])) { ?>
<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>ورود به پنل</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: #0f172a; display: flex; justify-content: center; align-items: center; height: 100vh; font-family: Tahoma; }
        .card { background: #1e293b; padding: 40px; border-radius: 12px; width: 340px; }
        h2 { color: #fff; text-align: center; margin-bottom: 24px; font-size: 18px; }
        input { width: 100%; padding: 12px; margin-bottom: 14px; border-radius: 8px; border: 1px solid #334155; background: #0f172a; color: #fff; font-size: 14px; }
        button { width: 100%; padding: 12px; background: #3b82f6; color: #fff; border: none; border-radius: 8px; font-size: 15px; cursor: pointer; }
        button:hover { background: #2563eb; }
        .error { color: #f87171; text-align: center; margin-bottom: 12px; font-size: 13px; }
    </style>
</head>
<body>
<div class="card">
    <h2>🤖 پنل ربات تامین فلات</h2>
    <?php if (isset($error)) echo "<p class='error'>{$error}</p>"; ?>
    <form method="POST">
        <input type="text"     name="user" placeholder="نام کاربری" required>
        <input type="password" name="pass" placeholder="رمز عبور"   required>
        <button type="submit">ورود</button>
    </form>
</div>
</body>
</html>
<?php
    exit;
}

// ========== dashboard ==========
$db      = new SQLite3('/var/www/bot/data/bot.db');
$users   = $db->querySingle("SELECT COUNT(*) FROM users");
$surveys = $db->querySingle("SELECT COUNT(*) FROM surveys");
$great   = $db->querySingle("SELECT COUNT(*) FROM surveys WHERE rating='عالی'");
$good    = $db->querySingle("SELECT COUNT(*) FROM surveys WHERE rating='خوب'");
$mid     = $db->querySingle("SELECT COUNT(*) FROM surveys WHERE rating='متوسط'");
$bad     = $db->querySingle("SELECT COUNT(*) FROM surveys WHERE rating='ضعیف'");

$recentUsers = $db->query("SELECT first_name, started_at FROM users ORDER BY id DESC LIMIT 5");
?>
<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>پنل ربات</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: #0f172a; color: #e2e8f0; font-family: Tahoma; padding: 30px; }
        h1 { font-size: 20px; margin-bottom: 24px; color: #fff; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 16px; margin-bottom: 30px; }
        .card { background: #1e293b; border-radius: 10px; padding: 20px; text-align: center; }
        .card .num { font-size: 32px; font-weight: bold; color: #3b82f6; }
        .card .label { font-size: 13px; color: #94a3b8; margin-top: 6px; }
        table { width: 100%; border-collapse: collapse; background: #1e293b; border-radius: 10px; overflow: hidden; }
        th { background: #334155; padding: 12px; font-size: 13px; }
        td { padding: 10px 12px; border-bottom: 1px solid #334155; font-size: 13px; }
        .logout { float: left; color: #f87171; text-decoration: none; font-size: 13px; }
    </style>
</head>
<body>
<h1>🤖 پنل ربات تامین فلات <a href="?logout" class="logout">خروج</a></h1>

<div class="grid">
    <div class="card"><div class="num"><?= $users ?></div><div class="label">کاربران</div></div>
    <div class="card"><div class="num"><?= $surveys ?></div><div class="label">نظرسنجی‌ها</div></div>
    <div class="card"><div class="num"><?= $great ?></div><div class="label">⭐ عالی</div></div>
    <div class="card"><div class="num"><?= $good ?></div><div class="label">✅ خوب</div></div>
    <div class="card"><div class="num"><?= $mid ?></div><div class="label">😐 متوسط</div></div>
    <div class="card"><div class="num"><?= $bad ?></div><div class="label">👎 ضعیف</div></div>
</div>

<table>
    <tr><th>نام کاربر</th><th>تاریخ عضویت</th></tr>
    <?php while ($row = $recentUsers->fetchArray()): ?>
    <tr>
        <td><?= htmlspecialchars($row['first_name']) ?></td>
        <td><?= $row['started_at'] ?></td>
    </tr>
    <?php endwhile; ?>
</table>

<?php
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: stats.php');
    exit;
}
?>
</body>
</html>