<?php

$config = require __DIR__ . '/config.php';

$ADMIN_USER = $config['admin_user'];
$ADMIN_PASS = $config['admin_pass'];

session_start();

/*
|--------------------------------------------------------------------------
| Login
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_SESSION['auth'])) {

    if (
        isset($_POST['user'], $_POST['pass']) &&
        $_POST['user'] === $ADMIN_USER &&
        $_POST['pass'] === $ADMIN_PASS
    ) {
        $_SESSION['auth'] = true;

        // جلوگیری از ارسال مجدد فرم Login
        header('Location: stats.php');
        exit;
    } else {
        $error = 'نام کاربری یا رمز عبور اشتباه است';
    }
}

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

if (isset($_GET['logout'])) {

    session_destroy();

    header('Location: stats.php');
    exit;
}

/*
|--------------------------------------------------------------------------
| PDF Upload
|--------------------------------------------------------------------------
*/

$uploadMessage = null;

if (
    isset($_SESSION['auth']) &&
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_FILES['price_pdf'])
) {

    $file = $_FILES['price_pdf'];

    if (
        $file['error'] === UPLOAD_ERR_OK &&
        $file['type'] === 'application/pdf'
    ) {

        $dest = '/var/www/bot/assets/files/price-list.pdf';

        if (move_uploaded_file($file['tmp_name'], $dest)) {

            $uploadMessage = [
                'type' => 'success',
                'text' => 'فایل با موفقیت بارگذاری و جایگزین شد.'
            ];
        } else {

            $uploadMessage = [
                'type' => 'error',
                'text' => 'خطا در ذخیره فایل روی سرور.'
            ];
        }
    } else {

        $uploadMessage = [
            'type' => 'error',
            'text' => 'فیلد فایل PDF الزامی است.'
        ];
    }
}

/*
|--------------------------------------------------------------------------
| Login Page
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['auth'])) {
?>

    <!DOCTYPE html>
    <html dir="rtl">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ورود - تامین فلات</title>
        <style>
            @font-face {
                font-family: 'Kalameh';
                src: url('../assets/fonts/KalamehWeb(FaNum)-Regular.woff2') format('woff2');
                font-weight: normal;
            }

            @font-face {
                font-family: 'Kalameh';
                src: url('../assets/fonts/KalamehWeb(FaNum)-Medium.woff2') format('woff2');
                font-weight: 500;
            }

            @font-face {
                font-family: 'Kalameh';
                src: url('../assets/fonts/KalamehWeb(FaNum)-SemiBold.woff2') format('woff2');
                font-weight: 600;
            }

            @font-face {
                font-family: 'Kalameh';
                src: url('../assets/fonts/KalamehWeb(FaNum)-Bold.woff2') format('woff2');
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
                margin-bottom: 24px;
                gap: 6px;
            }

            h2 {
                color: #1e293b;
                font-size: 16px;
            }

            h1 {
                color: #1e293b;
                font-size: 22px;
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

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    width="32"
                    height="32"
                    fill="none"
                    stroke="#3b82f6"
                    stroke-width="1.5">
                    <path
                        d="M19 16V14C19 11.1716 19 9.75736 18.1213 8.87868C17.2426 8 15.8284 8 13 8H11C8.17157 8 6.75736 8 5.87868 8.87868C5 9.75736 5 11.1716 5 14V16C5 18.8284 5 20.2422 5.87868 21.1213C6.75736 22 8.17157 22 11 22H13C15.8284 22 17.2422 22 18.1213 21.1213C19 20.2422 19 18.8284 19 16Z"
                        stroke-linejoin="round" />

                    <path
                        d="M13.5 3.5C13.5 4.32843 12.8284 5 12 5C11.1716 5 10.5 4.32843 10.5 3.5C10.5 2.67157 11.1716 2 12 2C12.8284 2 13.5 2.67157 13.5 3.5Z" />

                    <path d="M12 5V8" stroke-linecap="round" />

                    <path d="M9 13V14" stroke-linecap="round" />

                    <path d="M15 13V14" stroke-linecap="round" />

                    <path
                        d="M10 17.5C10 17.5 10.6667 18 12 18C13.3333 18 14 17.5 14 17.5"
                        stroke-linecap="round" />
                </svg>

                <h2>تامین فلات</h2>

                <h1>ورود به داشبورد</h1>

            </div>

            <?php if (isset($error)): ?>

                <p class="error">
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </p>

            <?php endif; ?>

            <form method="POST">

                <input
                    type="text"
                    name="user"
                    placeholder="نام کاربری"
                    required>

                <input
                    type="password"
                    name="pass"
                    placeholder="رمز عبور"
                    required>

                <button type="submit">
                    ورود
                </button>

            </form>

        </div>

    </body>

    </html>

<?php
    exit;
}

/*
|--------------------------------------------------------------------------
| Database
|--------------------------------------------------------------------------
*/

$db = new SQLite3('/var/www/bot/data/bot.db');

$users = $db->querySingle(
    "SELECT COUNT(*) FROM users"
);

$surveys = $db->querySingle(
    "SELECT COUNT(*) FROM surveys"
);

$great = $db->querySingle(
    "SELECT COUNT(*) FROM surveys WHERE rating='عالی'"
);

$good = $db->querySingle(
    "SELECT COUNT(*) FROM surveys WHERE rating='خوب'"
);

$mid = $db->querySingle(
    "SELECT COUNT(*) FROM surveys WHERE rating='متوسط'"
);

$bad = $db->querySingle(
    "SELECT COUNT(*) FROM surveys WHERE rating='ضعیف'"
);

$allUsers = $db->query(
    "SELECT first_name, started_at
     FROM users
     ORDER BY id DESC"
);

$allSurveys = $db->query(
    "SELECT first_name, rating, created_at
     FROM surveys
     ORDER BY id DESC"
);

$activePage = $_GET['page'] ?? 'users';

$pdfExists = file_exists(
    '/var/www/bot/assets/files/price-list.pdf'
);

$pdfDate = $pdfExists
    ? date(
        'Y/m/d H:i',
        filemtime('/var/www/bot/assets/files/price-list.pdf')
    )
    : null;

?>

<!DOCTYPE html>
<html dir="rtl">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>داشبورد - تامین فلات</title>

    <style>
        @font-face {
            font-family: 'Kalameh';
            src: url('../assets/fonts/KalamehWeb(FaNum)-Regular.woff2') format('woff2');
            font-weight: normal;
        }

        @font-face {
            font-family: 'Kalameh';
            src: url('../assets/fonts/KalamehWeb(FaNum)-Medium.woff2') format('woff2');
            font-weight: 500;
        }

        @font-face {
            font-family: 'Kalameh';
            src: url('../assets/fonts/KalamehWeb(FaNum)-SemiBold.woff2') format('woff2');
            font-weight: 600;
        }

        @font-face {
            font-family: 'Kalameh';
            src: url('../assets/fonts/KalamehWeb(FaNum)-Bold.woff2') format('woff2');
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
            display: flex;
            min-height: 100vh;
        }

        /*
        |--------------------------------------------------------------------------
        | Sidebar
        |--------------------------------------------------------------------------
        */

        .sidebar {
            width: 240px;
            background: #fff;
            border-left: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            padding: 24px 0;
            position: fixed;
            top: 0;
            right: 0;
            bottom: 0;
            z-index: 1000;
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0 20px 24px;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 16px;
        }

        .sidebar-logo span {
            font-size: 16px;
            font-weight: 600;
            color: #1e293b;
        }

        .sidebar nav a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 20px;
            color: #64748b;
            text-decoration: none;
            font-size: 14px;
            border-radius: 8px;
            margin: 2px 12px;
            transition: all 0.15s;
        }

        .sidebar nav a:hover {
            background: #f1f5f9;
            color: #1e293b;
        }

        .sidebar nav a.active {
            background: #eff6ff;
            color: #2563eb;
            font-weight: 600;
        }

        .sidebar nav a.active svg {
            stroke: #2563eb;
        }

        .sidebar-footer {
            margin-top: auto;
            padding: 16px 12px 0;
            border-top: 1px solid #e2e8f0;
        }

        .logout {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: #ef4444;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 13px;
            cursor: pointer;
            text-decoration: none;
            width: 100%;
            justify-content: center;
        }

        .logout:hover {
            background: #dc2626;
        }

        /*
        |--------------------------------------------------------------------------
        | Main
        |--------------------------------------------------------------------------
        */

        .main {
            margin-right: 240px;
            padding: 30px;
            flex: 1;
        }

        .page-header {
            margin-bottom: 24px;
        }

        .page-header h1 {
            font-size: 20px;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .page-header p {
            color: #64748b;
            font-size: 13px;
            margin-top: 4px;
        }

        /*
        |--------------------------------------------------------------------------
        | Stats
        |--------------------------------------------------------------------------
        */

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
        }

        .stat-card .num {
            font-size: 30px;
            font-weight: bold;
            color: #3b82f6;
        }

        .stat-card .label {
            font-size: 12px;
            color: #64748b;
            margin-top: 4px;
        }

        /*
        |--------------------------------------------------------------------------
        | Tables
        |--------------------------------------------------------------------------
        */

        .table-card {
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            margin-bottom: 24px;
        }

        .table-card-header {
            padding: 16px 20px;
            border-bottom: 1px solid #f1f5f9;
            font-size: 14px;
            font-weight: 600;
            color: #475569;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
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
            border-bottom: 1px solid #f8fafc;
            font-size: 13px;
            color: #334155;
        }

        tr:last-child td {
            border-bottom: none;
        }

        /*
        |--------------------------------------------------------------------------
        | Badges
        |--------------------------------------------------------------------------
        */

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

        /*
        |--------------------------------------------------------------------------
        | Upload
        |--------------------------------------------------------------------------
        */

        .upload-card {
            background: #fff;
            border-radius: 10px;
            padding: 24px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.06);
            margin-bottom: 24px;
        }

        .upload-card h2 {
            font-size: 15px;
            color: #475569;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .upload-zone {
            border: 2px dashed #cbd5e1;
            border-radius: 10px;
            padding: 40px;
            text-align: center;
            background: #f8fafc;
            cursor: pointer;
            transition: all 0.2s;
            margin-bottom: 16px;
        }

        .upload-zone:hover {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .upload-zone p {
            color: #64748b;
            font-size: 14px;
            margin-top: 8px;
        }

        .upload-zone small {
            color: #94a3b8;
            font-size: 12px;
        }

        #price_pdf {
            display: none;
        }

        .btn-upload {
            padding: 12px 24px;
            background: #3b82f6;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            cursor: pointer;
        }

        .btn-upload:hover {
            background: #2563eb;
        }

        .file-info {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 12px 16px;
            font-size: 13px;
            color: #16a34a;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert.success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #16a34a;
        }

        .alert.error {
            background: #fef2f2;
            border: 1px solid #fecaca;
            color: #dc2626;
        }

        /*
        |--------------------------------------------------------------------------
        | Mobile Menu Button
        |--------------------------------------------------------------------------
        */

        .mobile-menu-btn {
            display: none;
        }

        .menu-overlay {
            display: none;
        }

        /*
        |--------------------------------------------------------------------------
        | Mobile
        |--------------------------------------------------------------------------
        */

        @media (max-width: 768px) {

            body {
                flex-direction: column;
            }

            /*
            | همبرگر
            */

            .mobile-menu-btn {
                display: flex;
                position: fixed;
                top: 14px;
                right: 14px;
                z-index: 1002;

                width: 44px;
                height: 44px;

                border: none;
                border-radius: 10px;

                background: #fff;

                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.12);

                align-items: center;
                justify-content: center;

                flex-direction: column;
                gap: 5px;

                cursor: pointer;
            }

            .mobile-menu-btn span {
                display: block;

                width: 20px;
                height: 2px;

                background: #334155;

                border-radius: 2px;

                transition: all 1s ease;
            }

            /*
            | Sidebar
            */

            .sidebar {
                width: 240px;
                height: 100vh;

                position: fixed;

                top: 0;
                right: 0;
                bottom: auto;

                z-index: 1001;

                border-left: 1px solid #e2e8f0;
                border-bottom: none;

                padding: 24px 0;

                display: flex;
                flex-direction: column;

                transform: translateX(100%);
                transition: transform 0.25s ease;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            /*
            | Sidebar Logo
            */

            .sidebar-logo {
                display: flex;

                align-items: center;

                gap: 10px;

                padding: 0 20px 24px;

                border-bottom: 1px solid #e2e8f0;

                margin-bottom: 16px;
            }

            /*
            | Navigation
            */

            .sidebar nav {
                display: block;
            }

            .sidebar nav a {
                display: flex;

                align-items: center;

                gap: 10px;

                padding: 12px 20px;

                margin: 2px 12px;
            }

            /*
            | Footer
            */

            .sidebar-footer {
                margin-top: auto;

                padding: 16px 12px 0;

                border-top: 1px solid #e2e8f0;
            }

            /*
            | Main
            */

            .main {
                margin-right: 0;

                padding: 16px;

                padding-top: 70px;
            }

            /*
            | Overlay
            */

            .menu-overlay {
                display: block;

                position: fixed;

                inset: 0;

                z-index: 999;

                background: rgba(15, 23, 42, 0.35);

                opacity: 0;

                visibility: hidden;

                transition: all 0.25s ease;
            }

            .menu-overlay.active {
                opacity: 1;

                visibility: visible;
            }

            /*
            | Hamburger -> X
            */

            .mobile-menu-btn.active span:nth-child(1) {
                transform: translateY(7px) rotate(45deg);
            }

            .mobile-menu-btn.active span:nth-child(2) {
                opacity: 0;
            }

            .mobile-menu-btn.active span:nth-child(3) {
                transform: translateY(-7px) rotate(-45deg);
            }

            /*
            | Tables
            */

            table {
                display: block;

                overflow-x: auto;

                white-space: nowrap;
            }

            /*
            | Upload
            */

            .upload-zone {
                padding: 30px 20px;
            }

        }
    </style>
</head>

<body>
    <!-- Mobile Menu Button -->
    <button
        class="mobile-menu-btn"
        id="mobileMenuBtn"
        type="button"
        aria-label="باز کردن منو"
        aria-expanded="false">

        <span></span>
        <span></span>
        <span></span>

    </button>

    <!-- Mobile Overlay -->

    <div
        class="menu-overlay"
        id="menuOverlay"></div>

    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-logo">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                viewBox="0 0 24 24"
                width="28"
                height="28"
                fill="none"
                stroke="#3b82f6"
                stroke-width="1.5">
                <path
                    d="M19 16V14C19 11.1716 19 9.75736 18.1213 8.87868C17.2426 8 15.8284 8 13 8H11C8.17157 8 6.75736 8 5.87868 8.87868C5 9.75736 5 11.1716 5 14V16C5 18.8284 5 20.2422 5.87868 21.1213C6.75736 22 8.17157 22 11 22H13C15.8284 22 15.2422 22 18.1213 21.1213C19 20.2422 19 18.8284 19 16Z"
                    stroke-linejoin="round" />
                <path
                    d="M13.5 3.5C13.5 4.32843 12.8284 5 12 5C11.1716 5 10.5 4.32843 10.5 3.5C10.5 2.67157 11.1716 2 12 2C12.8284 2 13.5 2.67157 13.5 3.5Z" />
                <path
                    d="M12 5V8"
                    stroke-linecap="round" />
            </svg>
            <span>تامین فلات</span>
        </div>

        <nav>
            <!-- Dashboard -->
            <a
                href="?page=dashboard"
                class="<?= $activePage === 'dashboard' ? 'active' : '' ?>">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    width="18"
                    height="18"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round">
                    <path
                        d="M13 11C13 8.79086 11.2091 7 9 7C6.79086 7 5 8.79086 5 11C5 13.2091 6.79086 15 9 15C11.2091 15 13 13.2091 13 11Z" />
                    <path
                        d="M15 21C15 17.6863 12.3137 15 9 15C5.68629 15 3 17.6863 3 21" />
                    <path
                        d="M21 17C21 13.6863 18.3137 11 15 11" />
                    <path
                        d="M11.0386 7.55773C11.5412 6.60885 12.3702 6 13.5 6C15.433 6 17 7.567 17 9.5" />
                </svg>
                داشبورد
            </a>

            <!-- Users -->

            <a
                href="?page=users"
                class="<?= $activePage === 'users' ? 'active' : '' ?>">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    width="18"
                    height="18"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round">
                    <path
                        d="M13 11C13 8.79086 11.2091 7 9 7C6.79086 7 5 8.79086 5 11C5 13.2091 6.79086 15 9 15C11.2091 15 13 13.2091 13 11Z" />
                    <path
                        d="M15 21C15 17.6863 12.3137 15 9 15C5.68629 15 3 17.6863 3 21" />
                    <path
                        d="M21 17C21 13.6863 18.3137 11 15 11" />
                    <path
                        d="M11.0386 7.55773C11.5412 6.60885 12.3702 6 13.5 6C15.433 6 17 7.567 17 9.5" />
                </svg>
                کاربران
            </a>

            <!-- Surveys -->

            <a
                href="?page=surveys"
                class="<?= $activePage === 'surveys' ? 'active' : '' ?>">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    width="18"
                    height="18"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round">
                    <path
                        d="M15 21V6C15 5.06812 15 4.60218 14.8478 4.23463C14.6448 3.74458 14.2554 3.35523 13.7654 3.15224C13.3978 3 12.9319 3 12 3C11.0681 3 10.6022 3 10.2346 3.15224C9.74458 3.35523 9.35523 3.74458 9.15224 4.23463C9 4.60218 9 5.06812 9 6V21H15Z" />
                    <path
                        d="M17 8H15V21H17C18.8856 21 19.8284 21 20.4142 20.4142C21 19.8284 21 18.8856 21 17V12C21 10.1144 21 9.17157 20.4142 8.58579C20.8284 8 19.8856 8 17 8Z" />
                    <path
                        d="M9 13H7C5.11438 13 4.17157 13 3.58579 13.5858C3 14.1716 3 15.1144 3 17C3 18.8856 3 19.8284 3.58579 20.4142C4.17157 21 5.11438 21 7 21H9V13Z" />
                </svg>
                نظرسنجی‌ها
            </a>

            <!-- Routines -->

            <a
                href="?page=routines"
                class="<?= $activePage === 'routines' ? 'active' : '' ?>">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    width="18"
                    height="18"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round">
                    <path
                        d="M14 3.5H10C6.22876 3.5 4.34315 3.5 3.17157 4.67157C2 5.84315 2 7.72876 2 11.5V12.5C2 16.2712 2 18.1569 3.17157 19.3284C4.34315 20.5 6.22876 20.5 10 20.5H14C17.7712 20.5 19.6569 20.5 20.8284 19.3284C22 18.1569 22 16.2712 22 12.5V11.5C22 7.72876 22 5.84315 20.8284 4.67157C19.6569 3.5 17.7712 3.5 14 3.5Z" />
                    <path
                        d="M8 12H16M8 8.5H16M8 15.5H13" />
                </svg>
                روتین‌های ربات
            </a>
        </nav>

        <!-- Logout -->

        <div class="sidebar-footer">
            <a
                href="?logout"
                class="logout">
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 24 24"
                    width="16"
                    height="16"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.5"
                    stroke-linecap="round">
                    <path
                        d="M21 12H14M19.5 9.5C19.5 9.5 22 11.3412 22 12C22 12.6588 19.5 14.5 19.5 14.5" />
                    <path
                        d="M11 4H8C6.11438 4 5.17157 4 4.58579 4.58579C4 5.17157 4 6.11438 4 8V16C4 17.8856 4 18.8284 4.58579 19.4142C5.17157 20 6.11438 20 8 20H11" />
                </svg>
                خروج
            </a>
        </div>
    </aside>

    <!-- Main -->

    <main class="main">
        <?php if ($activePage === 'dashboard'): ?>
            <div class="page-header">
                <h1>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        width="22"
                        height="22"
                        fill="none"
                        stroke="#3b82f6"
                        stroke-width="1.5"
                        stroke-linecap="round">
                        <path
                            d="M13 11C13 8.79086 11.2091 7 9 7C6.79086 7 5 8.79086 5 11C5 13.2091 6.79086 15 9 15C11.2091 15 13 13.2091 13 11Z" />
                        <path
                            d="M15 21C15 17.6863 12.3137 15 9 15C5.68629 15 3 17.6863 3 21" />
                        <path
                            d="M21 17C21 13.6863 18.3137 11 15 11" />
                    </svg>
                    داشبورد
                </h1>
            </div>

            <div class="grid">
                <div class="stat-card">
                    <div class="num">
                        <?= $users ?>
                    </div>
                    <div class="label">
                        کل کاربران
                    </div>
                </div>

                <div class="stat-card">
                    <div class="num">
                        <?= $surveys ?>
                    </div>

                    <div class="label">
                        کل نظرها
                    </div>
                </div>
            </div>

        <?php elseif ($activePage === 'users'): ?>
            <div class="page-header">
                <h1>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        width="22"
                        height="22"
                        fill="none"
                        stroke="#3b82f6"
                        stroke-width="1.5"
                        stroke-linecap="round">
                        <path
                            d="M13 11C13 8.79086 11.2091 7 9 7C6.79086 7 5 8.79086 5 11C5 13.2091 6.79086 15 9 15C11.2091 15 13 13.2091 13 11Z" />
                        <path
                            d="M15 21C15 17.6863 12.3137 15 9 15C5.68629 15 3 17.6863 3 21" />
                        <path
                            d="M21 17C21 13.6863 18.3137 11 15 11" />
                    </svg>
                    کاربران
                </h1>
            </div>

            <div class="table-card">
                <table>
                    <tr>
                        <th>
                            نام کاربر
                        </th>
                        <th>
                            تاریخ عضویت
                        </th>
                    </tr>

                    <?php while ($row = $allUsers->fetchArray()): ?>
                        <tr>
                            <td>
                                <?= htmlspecialchars(
                                    $row['first_name'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars(
                                    $row['started_at'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>

                </table>
            </div>

        <?php elseif ($activePage === 'surveys'): ?>
            <div class="page-header">
                <h1>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        width="22"
                        height="22"
                        fill="none"
                        stroke="#3b82f6"
                        stroke-width="1.5"
                        stroke-linecap="round">
                        <path
                            d="M15 21V6C15 5.06812 15 4.60218 14.8478 4.23463C14.6448 3.74458 14.2554 3.35523 13.7654 3.15224C13.3978 3 12.9319 3 12 3C11.0681 3 10.6022 3 10.2346 3.15224C9.74458 3.35523 9.35523 3.74463 9.15224 4.23463C9 4.60218 9 5.06812 9 6V21H15Z" />
                        <path
                            d="M17 8H15V21H17C18.8856 21 19.8284 21 20.4142 20.4142C21 19.8284 21 18.8856 21 17V12C21 10.1144 21 9.17157 20.4142 8.58579C20 8 18.8856 8 17 8Z" />
                        <path
                            d="M9 13H7C5.11438 13 4.17157 13 3.58579 13.5858C3 14.1716 3 15.1144 3 17C3 18.8856 3 19.8284 3.58579 20.4142C4.17157 21 5.11438 21 7 21H9V13Z" />
                    </svg>
                    نظرسنجی‌ها
                </h1>
            </div>

            <div class="table-card">
                <table>

                    <tr>
                        <th>
                            نام کاربر
                        </th>
                        <th>
                            نظر
                        </th>
                        <th>
                            تاریخ
                        </th>
                    </tr>

                    <?php while ($row = $allSurveys->fetchArray()): ?>
                        <tr>
                            <td>
                                <?= htmlspecialchars(
                                    $row['first_name'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>
                            <td>
                                <span
                                    class="badge <?= htmlspecialchars(
                                                        $row['rating'],
                                                        ENT_QUOTES,
                                                        'UTF-8'
                                                    ) ?>">
                                    <?= htmlspecialchars(
                                        $row['rating'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>
                                </span>
                            </td>
                            <td>
                                <?= htmlspecialchars(
                                    $row['created_at'],
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>

                </table>
            </div>

        <?php elseif ($activePage === 'routines'): ?>
            <div class="page-header">
                <h1>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        width="22"
                        height="22"
                        fill="none"
                        stroke="#3b82f6"
                        stroke-width="1.5"
                        stroke-linecap="round">
                        <path
                            d="M14 3.5H10C6.22876 3.5 4.34315 3.5 3.17157 4.67157C2 5.84315 2 7.72876 2 11.5V12.5C2 16.2712 2 18.1569 3.17157 19.3284C4.34315 20.5 6.22876 20.5 10 20.5H14C17.7712 20.5 19.6569 20.5 20.8284 19.3284C22 18.1569 22 16.2712 22 12.5V11.5C22 7.72876 22 5.84315 20.8284 4.67157C19.6569 3.5 17.7712 3.5 14 3.5Z" />
                        <path
                            d="M8 12H16M8 8.5H16M8 15.5H13" />
                    </svg>
                    روتین‌های ربات
                </h1>
            </div>

            <?php if ($uploadMessage): ?>
                <div
                    class="alert <?= htmlspecialchars(
                                        $uploadMessage['type'],
                                        ENT_QUOTES,
                                        'UTF-8'
                                    ) ?>">
                    <?= htmlspecialchars(
                        $uploadMessage['text'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </div>
            <?php endif; ?>

            <div class="upload-card">
                <h2>
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        width="18"
                        height="18"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.5"
                        stroke-linecap="round">
                        <path
                            d="M12 15V3M12 3L8 7M12 3L16 7" />
                        <path
                            d="M3 15C3 18.866 6.13401 22 10 22H14C17.866 22 21 18.866 21 15" />
                    </svg>
                    آپلود فهرست اقلام و قیمت
                </h2>

                <form
                    method="POST"
                    enctype="multipart/form-data">
                    <div
                        class="upload-zone"
                        onclick="document.getElementById('price_pdf').click()">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            width="40"
                            height="40"
                            fill="none"
                            stroke="#94a3b8"
                            stroke-width="1.5"
                            stroke-linecap="round">
                            <path
                                d="M12 15V3M12 3L8 7M12 3L16 7" />

                            <path
                                d="M3 15C3 18.866 6.13401 22 10 22H14C17.866 22 21 18.866 21 15" />
                        </svg>

                        <p id="file-name-label">
                            برای انتخاب فایل کلیک کنید
                        </p>

                        <small>
                            فقط فایل PDF پشتیبانی می‌شود
                        </small>
                    </div>

                    <input
                        type="file"
                        name="price_pdf"
                        id="price_pdf"
                        accept="application/pdf">
                    <button
                        type="submit"
                        class="btn-upload">
                        📥 ایمپورت فایل و بروزرسانی
                    </button>
                </form>
            </div>

            <script>
                document
                    .getElementById('price_pdf')
                    .addEventListener('change', function() {

                        const label =
                            document.getElementById('file-name-label');

                        label.textContent =
                            this.files[0] ?
                            this.files[0].name :
                            'برای انتخاب فایل کلیک کنید';

                    });
            </script>
        <?php endif; ?>

    </main>

    <script>
        const menuBtn = document.getElementById('mobileMenuBtn');
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.getElementById('menuOverlay');

        function openMenu() {
            sidebar.classList.add('open');
            menuBtn.classList.add('active');
            overlay.classList.add('active');
            menuBtn.setAttribute(
                'aria-expanded',
                'true'
            );
        }

        function closeMenu() {
            sidebar.classList.remove('open');
            menuBtn.classList.remove('active');
            overlay.classList.remove('active');
            menuBtn.setAttribute(
                'aria-expanded',
                'false'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Hamburger
        |--------------------------------------------------------------------------
        */

        menuBtn.addEventListener(
            'click',
            openMenu
        );

        /*
        |--------------------------------------------------------------------------
        | Overlay
        |--------------------------------------------------------------------------
        */

        overlay.addEventListener(
            'click',
            closeMenu
        );

        /*
        |--------------------------------------------------------------------------
        | Close menu after clicking a navigation item
        |--------------------------------------------------------------------------
        */

        sidebar
            .querySelectorAll('nav a')
            .forEach(function(link) {
                link.addEventListener(
                    'click',
                    closeMenu
                );
            });

        /*
        |--------------------------------------------------------------------------
        | Close menu after clicking logout
        |--------------------------------------------------------------------------
        */

        const logout =
            sidebar.querySelector('.logout');
        if (logout) {
            logout.addEventListener(
                'click',
                closeMenu
            );
        }

        /*
        |--------------------------------------------------------------------------
        | ESC closes menu
        |--------------------------------------------------------------------------
        */

        document.addEventListener(
            'keydown',
            function(event) {
                if (
                    event.key === 'Escape' &&
                    sidebar.classList.contains('open')
                ) {
                    closeMenu();
                }
            }
        );

        /*
        |--------------------------------------------------------------------------
        | When resizing back to desktop
        |--------------------------------------------------------------------------
        */

        window.addEventListener(
            'resize',
            function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('open');
                    menuBtn.classList.remove('active');
                    overlay.classList.remove('active');
                    menuBtn.setAttribute(
                        'aria-expanded',
                        'false'
                    );
                }
            }
        );
    </script>

</body>

</html>