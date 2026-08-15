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

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: stats.php');
    exit;
}

if (!isset($_SESSION['auth'])) { ?>
    <!DOCTYPE html>
    <html dir="rtl">

    <head>
        <meta charset="UTF-8">
        <title>ورود به پنل</title>
        <style>
            @font-face {
                font-family: 'Kalameh';
                src: url('assets/fonts/KalamehWeb(FaNum)-Regular.woff2') format('woff2');
                font-weight: normal;
            }

            @font-face {
                font-family: 'Kalameh';
                src: url('assets/fonts/KalamehWeb(FaNum)-Medium.woff2') format('woff2');
                font-weight: 500;
            }

            @font-face {
                font-family: 'Kalameh';
                src: url('assets/fonts/KalamehWeb(FaNum)-SemiBold.woff2') format('woff2');
                font-weight: 600;
            }

            @font-face {
                font-family: 'Kalameh';
                src: url('assets/fonts/KalamehWeb(FaNum)-Bold.woff2') format('woff2');
                font-weight: bold;
            }

            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }

            body {
                font-family: 'Kalameh';
                background: #0f172a;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                font-family: Tahoma;
            }

            .card {
                background: #1e293b;
                padding: 40px;
                border-radius: 12px;
                width: 340px;
            }

            h2 {
                color: #fff;
                text-align: center;
                margin-bottom: 24px;
                font-size: 18px;
            }

            input {
                width: 100%;
                padding: 12px;
                margin-bottom: 14px;
                border-radius: 8px;
                border: 1px solid #334155;
                background: #0f172a;
                color: #fff;
                font-size: 14px;
            }

            button {
                width: 100%;
                padding: 12px;
                background: #3b82f6;
                color: #fff;
                border: none;
                border-radius: 8px;
                font-size: 15px;
                cursor: pointer;
            }

            button:hover {
                background: #2563eb;
            }

            .error {
                color: #f87171;
                text-align: center;
                margin-bottom: 12px;
                font-size: 13px;
            }
        </style>
    </head>

    <body>
        <div class="card">
            <h2>🤖 پنل ربات تامین فلات</h2>
            <?php if (isset($error)) echo "<p class='error'>{$error}</p>"; ?>
            <form method="POST">
                <input type="text" name="user" placeholder="نام کاربری" required>
                <input type="password" name="pass" placeholder="رمز عبور" required>
                <button type="submit">ورود</button>
            </form>
        </div>
    </body>

    </html>
<?php exit;
}

// داشبورد
$db      = new SQLite3('/var/www/bot/data/bot.db');
$users   = $db->querySingle("SELECT COUNT(*) FROM users");
$surveys = $db->querySingle("SELECT COUNT(*) FROM surveys");
$great   = $db->querySingle("SELECT COUNT(*) FROM surveys WHERE rating='عالی'");
$good    = $db->querySingle("SELECT COUNT(*) FROM surveys WHERE rating='خوب'");
$mid     = $db->querySingle("SELECT COUNT(*) FROM surveys WHERE rating='متوسط'");
$bad     = $db->querySingle("SELECT COUNT(*) FROM surveys WHERE rating='ضعیف'");

$allUsers   = $db->query("SELECT first_name, started_at FROM users ORDER BY id DESC");
$allSurveys = $db->query("SELECT first_name, rating, created_at FROM surveys ORDER BY id DESC");
?>
<!DOCTYPE html>
<html dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>پنل ربات</title>
    <style>
        @font-face {
            font-family: 'Kalameh';
            src: url('assets/fonts/KalamehWeb(FaNum)-Regular.woff2') format('woff2');
            font-weight: normal;
        }

        @font-face {
            font-family: 'Kalameh';
            src: url('assets/fonts/KalamehWeb(FaNum)-Medium.woff2') format('woff2');
            font-weight: 500;
        }

        @font-face {
            font-family: 'Kalameh';
            src: url('assets/fonts/KalamehWeb(FaNum)-SemiBold.woff2') format('woff2');
            font-weight: 600;
        }

        @font-face {
            font-family: 'Kalameh';
            src: url('assets/fonts/KalamehWeb(FaNum)-Bold.woff2') format('woff2');
            font-weight: bold;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Kalameh';
            background: #0f172a;
            color: #e2e8f0;
            padding: 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        h1 {
            font-size: 20px;
            color: #fff;
        }

        .logout {
            color: #f87171;
            text-decoration: none;
            font-size: 13px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 16px;
            margin-bottom: 30px;
        }

        .card {
            background: #1e293b;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
        }

        .card .num {
            font-size: 32px;
            font-weight: bold;
            color: #3b82f6;
        }

        .card .label {
            font-size: 13px;
            color: #94a3b8;
            margin-top: 6px;
        }

        .section {
            margin-bottom: 30px;
        }

        .section h2 {
            font-size: 15px;
            color: #94a3b8;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #1e293b;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #1e293b;
            border-radius: 10px;
            overflow: hidden;
        }

        th {
            background: #334155;
            padding: 12px 16px;
            font-size: 13px;
            text-align: right;
        }

        td {
            padding: 10px 16px;
            border-bottom: 1px solid #0f172a;
            font-size: 13px;
        }

        tr:last-child td {
            border-bottom: none;
        }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
        }

        .badge.عالی {
            background: #14532d;
            color: #86efac;
        }

        .badge.خوب {
            background: #1e3a5f;
            color: #93c5fd;
        }

        .badge.متوسط {
            background: #3f3a00;
            color: #fde68a;
        }

        .badge.ضعیف {
            background: #4c0519;
            color: #fca5a5;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>🤖 پنل ربات تامین فلات</h1>
        <a href="?logout" class="logout">خروج</a>
    </div>

    <div class="grid">
        <div class="card">
            <div class="num"><?= $users ?></div>
            <div class="label">👥 کاربران</div>
        </div>
        <div class="card">
            <div class="num"><?= $surveys ?></div>
            <div class="label">📊 نظرسنجی‌ها</div>
        </div>
        <div class="card">
            <div class="num"><?= $great ?></div>
            <div class="label">⭐ عالی</div>
        </div>
        <div class="card">
            <div class="num"><?= $good ?></div>
            <div class="label">✅ خوب</div>
        </div>
        <div class="card">
            <div class="num"><?= $mid ?></div>
            <div class="label">😐 متوسط</div>
        </div>
        <div class="card">
            <div class="num"><?= $bad ?></div>
            <div class="label">👎 ضعیف</div>
        </div>
    </div>

    <div class="section">
        <h2>📝 نظرسنجی‌ها</h2>
        <table>
            <tr>
                <th>نام کاربر</th>
                <th>نظر</th>
                <th>تاریخ</th>
            </tr>
            <?php while ($row = $allSurveys->fetchArray()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['first_name']) ?></td>
                    <td><span class="badge <?= $row['rating'] ?>"><?= $row['rating'] ?></span></td>
                    <td><?= $row['created_at'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <div class="section">
        <h2>👥 کاربران</h2>
        <table>
            <tr>
                <th>نام کاربر</th>
                <th>تاریخ عضویت</th>
            </tr>
            <?php while ($row = $allUsers->fetchArray()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['first_name']) ?></td>
                    <td><?= $row['started_at'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

</body>

</html>