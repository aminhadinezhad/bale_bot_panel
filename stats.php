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
        <title>ورود - تامین فلات</title>
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

            .login-header {
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
            }

            h2 {
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 6px;
                color: #1e293b;
                text-align: center;
                font-size: 18px;
            }

            h2:nth-child(1) {
                margin-bottom: 6px;
            }

            h2:nth-child(3) {
                margin-bottom: 12px;
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
                color: #ef4444;
                text-align: center;
                margin-bottom: 12px;
                font-size: 13px;
            }
        </style>
    </head>

    <body>
        <div class="card">
            <div class="login-header">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" color="currentColor" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M13 7H11C8.19108 7 6.78661 7 5.77772 7.67412C5.34096 7.96596 4.96596 8.34096 4.67412 8.77772C4 9.78661 4 11.1911 4 14C4 16.8089 4 18.2134 4.67412 19.2223C4.96596 19.659 5.34096 20.034 5.77772 20.3259C6.78661 21 8.19108 21 11 21H13C15.8089 21 17.2134 21 18.2223 20.3259C18.659 20.034 19.034 19.659 19.3259 19.2223C20 18.2134 20 16.8089 20 14C20 11.1911 20 9.78661 19.3259 8.77772C19.034 8.34096 18.659 7.96596 18.2223 7.67412C17.2134 7 15.8089 7 13 7Z"></path>
                    <path d="M4 14H2"></path>
                    <path d="M10 17H14"></path>
                    <path d="M22 14H20"></path>
                    <path d="M15 11V13"></path>
                    <path d="M9 11V13"></path>
                    <path d="M12 7C12 5.11438 12 4.17157 11.4142 3.58579C10.8284 3 9.88562 3 8 3"></path>
                </svg>
                <h2>
                    تامین فلات
                </h2>
                <h2>
                    ورود
                </h2>
            </div>
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
    <title>داشبورد - تامین فلات</title>
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
            display: flex;
            align-items: center;
            gap: 4px;
            padding: 8px 16px;
            background: #ef4444;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
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
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 4px;
            font-size: 13px;
            color: #64748b;
            margin-top: 6px;
        }

        .section {
            margin-bottom: 30px;
        }

        .section h2 {
            display: flex;
            align-items: center;
            gap: 4px;
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
        <h1><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" color="currentColor" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round">
                <path d="M10.5 8.75V6.75C10.5 5.10626 10.5 4.28439 10.046 3.73121C9.96291 3.62995 9.87005 3.53709 9.76879 3.45398C9.21561 3 8.39374 3 6.75 3C5.10626 3 4.28439 3 3.73121 3.45398C3.62995 3.53709 3.53709 3.62995 3.45398 3.73121C3 4.28439 3 5.10626 3 6.75V8.75C3 10.3937 3 11.2156 3.45398 11.7688C3.53709 11.8701 3.62995 11.9629 3.73121 12.046C4.28439 12.5 5.10626 12.5 6.75 12.5C8.39374 12.5 9.21561 12.5 9.76879 12.046C9.87005 11.9629 9.96291 11.8701 10.046 11.7688C10.5 11.2156 10.5 10.3937 10.5 8.75Z"></path>
                <path d="M7.75 15.5H5.75C5.05222 15.5 4.70333 15.5 4.41943 15.5861C3.78023 15.78 3.28002 16.2802 3.08612 16.9194C3 17.2033 3 17.5522 3 18.25C3 18.9478 3 19.2967 3.08612 19.5806C3.28002 20.2198 3.78023 20.72 4.41943 20.9139C4.70333 21 5.05222 21 5.75 21H7.75C8.44778 21 8.79667 21 9.08057 20.9139C9.71977 20.72 10.22 20.2198 10.4139 19.5806C10.5 19.2967 10.5 18.9478 10.5 18.25C10.5 17.5522 10.5 17.2033 10.4139 16.9194C10.22 16.2802 9.71977 15.78 9.08057 15.5861C8.79667 15.5 8.44778 15.5 7.75 15.5Z"></path>
                <path d="M21 17.25V15.25C21 13.6063 21 12.7844 20.546 12.2312C20.4629 12.1299 20.3701 12.0371 20.2688 11.954C19.7156 11.5 18.8937 11.5 17.25 11.5C15.6063 11.5 14.7844 11.5 14.2312 11.954C14.1299 12.0371 14.0371 12.1299 13.954 12.2312C13.5 12.7844 13.5 13.6063 13.5 15.25V17.25C13.5 18.8937 13.5 19.7156 13.954 20.2688C14.0371 20.3701 14.1299 20.4629 14.2312 20.546C14.7844 21 15.6063 21 17.25 21C18.8937 21 19.7156 21 20.2688 20.546C20.3701 20.4629 20.4629 20.3701 20.546 20.2688C21 19.7156 21 18.8937 21 17.25Z"></path>
                <path d="M18.25 3H16.25C15.5522 3 15.2033 3 14.9194 3.08612C14.2802 3.28002 13.78 3.78023 13.5861 4.41943C13.5 4.70333 13.5 5.05222 13.5 5.75C13.5 6.44778 13.5 6.79667 13.5861 7.08057C13.78 7.71977 14.2802 8.21998 14.9194 8.41388C15.2033 8.5 15.5522 8.5 16.25 8.5H18.25C18.9478 8.5 19.2967 8.5 19.5806 8.41388C20.2198 8.21998 20.72 7.71977 20.9139 7.08057C21 6.79667 21 6.44778 21 5.75C21 5.05222 21 4.70333 20.9139 4.41943C20.72 3.78023 20.2198 3.28002 19.5806 3.08612C19.2967 3 18.9478 3 18.25 3Z"></path>
            </svg>
            داشبورد</h1>
        <a href="?logout" class="logout">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" color="currentColor" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4.39267 4.00087C4 4.61597 4 5.41166 4 7.00304V16.997C4 18.5883 4 19.384 4.39267 19.9991C4.46279 20.109 4.5414 20.2132 4.62777 20.3108C5.11144 20.8572 5.87666 21.0758 7.4071 21.513C8.9414 21.9513 9.70856 22.1704 10.264 21.8417C10.3604 21.7847 10.45 21.7171 10.5313 21.6402C11 21.1965 11 20.3988 11 18.8034V5.19662C11 3.60122 11 2.80351 10.5313 2.35982C10.45 2.28288 10.3604 2.21527 10.264 2.15827C9.70856 1.82956 8.9414 2.0487 7.4071 2.48699C5.87666 2.92418 5.11144 3.14278 4.62777 3.68925C4.5414 3.78684 4.46279 3.89103 4.39267 4.00087Z"></path>
                <path d="M11 4H13.0171C14.9188 4 15.8696 4 16.4604 4.58579C16.7898 4.91238 16.9355 5.34994 17 6M11 20H13.0171C14.9188 20 15.8696 20 16.4604 19.4142C16.7898 19.0876 16.9355 18.6501 17 18"></path>
                <path d="M21 12H14M19.5 9.49994C19.5 9.49994 22 11.3412 22 12C22 12.6588 19.5 14.4999 19.5 14.4999"></path>
            </svg>
            خروج</a>
    </div>

    <div class="grid">
        <div class="card">
            <div class="num"><?= $users ?></div>
            <div class="label"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" color="currentColor" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M13 11C13 8.79086 11.2091 7 9 7C6.79086 7 5 8.79086 5 11C5 13.2091 6.79086 15 9 15C11.2091 15 13 13.2091 13 11Z"></path>
                    <path d="M11.0386 7.55773C11.0131 7.37547 11 7.18927 11 7C11 4.79086 12.7909 3 15 3C17.2091 3 19 4.79086 19 7C19 9.20914 17.2091 11 15 11C14.2554 11 13.5584 10.7966 12.9614 10.4423"></path>
                    <path d="M15 21C15 17.6863 12.3137 15 9 15C5.68629 15 3 17.6863 3 21"></path>
                    <path d="M21 17C21 13.6863 18.3137 11 15 11"></path>
                </svg>کاربران</div>
        </div>
        <div class="card">
            <div class="num"><?= $surveys ?></div>
            <div class="label"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" color="currentColor" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 21V6C15 5.06812 15 4.60218 14.8478 4.23463C14.6448 3.74458 14.2554 3.35523 13.7654 3.15224C13.3978 3 12.9319 3 12 3C11.0681 3 10.6022 3 10.2346 3.15224C9.74458 3.35523 9.35523 3.74458 9.15224 4.23463C9 4.60218 9 5.06812 9 6V21H15Z"></path>
                    <path d="M17 8H15V21H17C18.8856 21 19.8284 21 20.4142 20.4142C21 19.8284 21 18.8856 21 17V12C21 10.1144 21 9.17157 20.4142 8.58579C19.8284 8 18.8856 8 17 8Z"></path>
                    <path d="M9 13H7C5.11438 13 4.17157 13 3.58579 13.5858C3 14.1716 3 15.1144 3 17C3 18.8856 3 19.8284 3.58579 20.4142C4.17157 21 5.11438 21 7 21H9V13Z"></path>
                </svg>نظرسنجی‌ها</div>
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
        <h2><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" color="currentColor" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M15 21V6C15 5.06812 15 4.60218 14.8478 4.23463C14.6448 3.74458 14.2554 3.35523 13.7654 3.15224C13.3978 3 12.9319 3 12 3C11.0681 3 10.6022 3 10.2346 3.15224C9.74458 3.35523 9.35523 3.74458 9.15224 4.23463C9 4.60218 9 5.06812 9 6V21H15Z"></path>
                <path d="M17 8H15V21H17C18.8856 21 19.8284 21 20.4142 20.4142C21 19.8284 21 18.8856 21 17V12C21 10.1144 21 9.17157 20.4142 8.58579C19.8284 8 18.8856 8 17 8Z"></path>
                <path d="M9 13H7C5.11438 13 4.17157 13 3.58579 13.5858C3 14.1716 3 15.1144 3 17C3 18.8856 3 19.8284 3.58579 20.4142C4.17157 21 5.11438 21 7 21H9V13Z"></path>
            </svg>نظرسنجی‌ها</h2>
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
        <h2><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" color="currentColor" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M13 11C13 8.79086 11.2091 7 9 7C6.79086 7 5 8.79086 5 11C5 13.2091 6.79086 15 9 15C11.2091 15 13 13.2091 13 11Z"></path>
                <path d="M11.0386 7.55773C11.0131 7.37547 11 7.18927 11 7C11 4.79086 12.7909 3 15 3C17.2091 3 19 4.79086 19 7C19 9.20914 17.2091 11 15 11C14.2554 11 13.5584 10.7966 12.9614 10.4423"></path>
                <path d="M15 21C15 17.6863 12.3137 15 9 15C5.68629 15 3 17.6863 3 21"></path>
                <path d="M21 17C21 13.6863 18.3137 11 15 11"></path>
            </svg>کاربران</h2>
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