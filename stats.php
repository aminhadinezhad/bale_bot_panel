<?php
$ADMIN_USER = 'taminfalat';
$ADMIN_PASS = 'Tf@2026#bot';

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
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                font-family: 'Kalameh', Tahoma;
                box-sizing: border-box;
                margin: 0;
                padding: 0;
            }

            body {
                background: #f1f5f9;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }

            .card {
                background: #fff;
                padding: 40px;
                border-radius: 12px;
                width: 340px;
                box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
            }

            h2 {
                color: #1e293b;
                text-align: center;
                margin-bottom: 24px;
                font-size: 18px;
            }

            input {
                width: 100%;
                padding: 12px;
                margin-bottom: 14px;
                border-radius: 8px;
                border: 1px solid #e2e8f0;
                background: #f8fafc;
                color: #1e293b;
                font-size: 14px;
                font-family: 'Kalameh', Tahoma;
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
                font-family: 'Kalameh', Tahoma;
            }

            button:hover {
                background: #2563eb;
            }

            .error {
                color: #ef4444;
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
            font-family: 'Kalameh', Tahoma;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background: #f1f5f9;
            color: #1e293b;
            padding: 30px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        h1 {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 20px;
            color: #1e293b;
        }

        .logout {
            display: inline-block;
            padding: 8px 16px;
            background: #ef4444;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
            font-family: 'Kalameh', Tahoma;
        }

        .logout:hover {
            background: #dc2626;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 16px;
            margin-bottom: 30px;
        }

        .card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        .card .num {
            font-size: 32px;
            font-weight: bold;
            color: #3b82f6;
        }

        .card .label {
            font-size: 13px;
            color: #64748b;
            margin-top: 6px;
        }

        .section {
            margin-bottom: 30px;
        }

        .section h2 {
            font-size: 15px;
            color: #64748b;
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
        }

        th {
            background: #f8fafc;
            padding: 12px 16px;
            font-size: 13px;
            text-align: right;
            color: #475569;
            border-bottom: 1px solid #e2e8f0;
        }

        td {
            padding: 10px 16px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13px;
            color: #334155;
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
            background: #dcfce7;
            color: #16a34a;
        }

        .badge.خوب {
            background: #dbeafe;
            color: #2563eb;
        }

        .badge.متوسط {
            background: #fef9c3;
            color: #ca8a04;
        }

        .badge.ضعیف {
            background: #fee2e2;
            color: #dc2626;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" color="currentColor" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M13 7H11C8.19108 7 6.78661 7 5.77772 7.67412C5.34096 7.96596 4.96596 8.34096 4.67412 8.77772C4 9.78661 4 11.1911 4 14C4 16.8089 4 18.2134 4.67412 19.2223C4.96596 19.659 5.34096 20.034 5.77772 20.3259C6.78661 21 8.19108 21 11 21H13C15.8089 21 17.2134 21 18.2223 20.3259C18.659 20.034 19.034 19.659 19.3259 19.2223C20 18.2134 20 16.8089 20 14C20 11.1911 20 9.78661 19.3259 8.77772C19.034 8.34096 18.659 7.96596 18.2223 7.67412C17.2134 7 15.8089 7 13 7Z"></path>
                <path d="M4 14H2"></path>
                <path d="M10 17H14"></path>
                <path d="M22 14H20"></path>
                <path d="M15 11V13"></path>
                <path d="M9 11V13"></path>
                <path d="M12 7C12 5.11438 12 4.17157 11.4142 3.58579C10.8284 3 9.88562 3 8 3"></path>
            </svg>
            پنل ربات تامین فلات</h1>
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
            <div class="label">😍 عالی</div>
        </div>
        <div class="card">
            <div class="num"><?= $good ?></div>
            <div class="label">😊 خوب</div>
        </div>
        <div class="card">
            <div class="num"><?= $mid ?></div>
            <div class="label">🙂 متوسط</div>
        </div>
        <div class="card">
            <div class="num"><?= $bad ?></div>
            <div class="label">☹️ ضعیف</div>
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