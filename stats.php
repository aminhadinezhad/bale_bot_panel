<?php

$config = require __DIR__ . '/config.php';

$ADMIN_USER = $config['admin_user'];
$ADMIN_PASS = $config['admin_pass'];

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_SESSION['auth'])) {
    if ($_POST['user'] === $ADMIN_USER && $_POST['pass'] === $ADMIN_PASS) {
        $_SESSION['auth'] = true;
    } else {
        $error = 'نام کاربری یا رمز عبور اشتباه است';
    }
}

if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: stats.php');
    exit;
}

$uploadMessage = null;
if (isset($_SESSION['auth']) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['price_pdf'])) {
    $file = $_FILES['price_pdf'];
    if ($file['error'] === UPLOAD_ERR_OK && $file['type'] === 'application/pdf') {
        $dest = '/var/www/bot/assets/files/price-list.pdf';
        if (move_uploaded_file($file['tmp_name'], $dest)) {
            $uploadMessage = ['type' => 'success', 'text' => 'فایل با موفقیت بارگذاری و جایگزین شد.'];
        } else {
            $uploadMessage = ['type' => 'error', 'text' => 'خطا در ذخیره فایل روی سرور.'];
        }
    } else {
        $uploadMessage = ['type' => 'error', 'text' => 'فیلد فایل PDF الزامی است.'];
    }
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
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" color="currentColor" fill="none" stroke="#3b82f6" stroke-width="1.5">
                    <path d="M19 16V14C19 11.1716 19 9.75736 18.1213 8.87868C17.2426 8 15.8284 8 13 8H11C8.17157 8 6.75736 8 5.87868 8.87868C5 9.75736 5 11.1716 5 14V16C5 18.8284 5 20.2426 5.87868 21.1213C6.75736 22 8.17157 22 11 22H13C15.8284 22 17.2426 22 18.1213 21.1213C19 20.2426 19 18.8284 19 16Z" stroke-linejoin="round"></path>
                    <path d="M19 18C20.4142 18 21.1213 18 21.5607 17.5607C22 17.1213 22 16.4142 22 15C22 13.5858 22 12.8787 21.5607 12.4393C21.1213 12 20.4142 12 19 12" stroke-linejoin="round"></path>
                    <path d="M5 18C3.58579 18 2.87868 18 2.43934 17.5607C2 17.1213 2 16.4142 2 15C2 13.5858 2 12.8787 2.43934 12.4393C2.87868 12 3.58579 12 5 12" stroke-linejoin="round"></path>
                    <path d="M13.5 3.5C13.5 4.32843 12.8284 5 12 5C11.1716 5 10.5 4.32843 10.5 3.5C10.5 2.67157 11.1716 2 12 2C12.8284 2 13.5 2.67157 13.5 3.5Z"></path>
                    <path d="M12 5V8" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M9 13V14" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M15 13V14" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M10 17.5C10 17.5 10.6667 18 12 18C13.3333 18 14 17.5 14 17.5" stroke-linecap="round"></path>
                </svg>
                <h2>تامین فلات</h2>
                <h1>ورود به داشبورد</h1>
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

$db      = new SQLite3('/var/www/bot/data/bot.db');
$users   = $db->querySingle("SELECT COUNT(*) FROM users");
$surveys = $db->querySingle("SELECT COUNT(*) FROM surveys");
$great   = $db->querySingle("SELECT COUNT(*) FROM surveys WHERE rating='عالی'");
$good    = $db->querySingle("SELECT COUNT(*) FROM surveys WHERE rating='خوب'");
$mid     = $db->querySingle("SELECT COUNT(*) FROM surveys WHERE rating='متوسط'");
$bad     = $db->querySingle("SELECT COUNT(*) FROM surveys WHERE rating='ضعیف'");

$allUsers   = $db->query("SELECT first_name, started_at FROM users ORDER BY id DESC");
$allSurveys = $db->query("SELECT first_name, rating, created_at FROM surveys ORDER BY id DESC");

$activePage = $_GET['page'] ?? 'dashboard';
$pdfExists  = file_exists('/var/www/bot/assets/files/price-list.pdf');
$pdfDate    = $pdfExists ? date('Y/m/d H:i', filemtime('/var/www/bot/assets/files/price-list.pdf')) : null;
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
            gap: 10px;
            padding: 0 20px 24px;
            border-bottom: 1px solid #e2e8f0;
            margin-bottom: 16px;
        }

        .sidebar-logo a {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .sidebar-logo>a>span {
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

        /* محتوا */
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
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: #3b82f6;
            color: #fff;
            border: none;
            border-radius: 8px;
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

        /* همبرگر */
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 12px;
            border: none;
            background: #fff !important;
            position: fixed;
            top: 16px;
            left: 16px;
            z-index: 1001;
            border-radius: 8px;
            box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
            transition: background 0.2s;
        }

        .hamburger:hover {
            background: #f1f5f9;
        }

        .hamburger span {
            display: block;
            width: 20px;
            height: 2px;
            background: #1e293b;
            border-radius: 2px;
            transition: all 0.3s;
        }

        .hamburger.open span:nth-child(1) {
            transform: translateY(7px) rotate(45deg);
        }

        .hamburger.open span:nth-child(2) {
            opacity: 0;
        }

        .hamburger.open span:nth-child(3) {
            transform: translateY(-7px) rotate(-45deg);
        }

        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.3);
            z-index: 999;
            backdrop-filter: blur(2px);
        }

        .overlay.show {
            display: block;
        }

        @media (max-width: 768px) {
            .hamburger {
                display: flex;
            }

            .sidebar {
                transform: translateX(100%);
                transition: transform 0.3s ease;
                width: 260px;
            }

            .sidebar.open {
                transform: translateX(0);
            }

            .main {
                margin-right: 0;
                padding: 20px 16px 16px;
            }

            table {
                overflow-x: auto;
            }
        }
    </style>
</head>

<body>

    <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

    <button class="hamburger" id="hamburger" onclick="toggleSidebar()">
        <span></span>
        <span></span>
        <span></span>
    </button>

    <aside class="sidebar">
        <div class="sidebar-logo">
            <a href="?page=dashboard">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" color="currentColor" fill="none" stroke="#3b82f6" stroke-width="1.5">
                    <path d="M19 16V14C19 11.1716 19 9.75736 18.1213 8.87868C17.2426 8 15.8284 8 13 8H11C8.17157 8 6.75736 8 5.87868 8.87868C5 9.75736 5 11.1716 5 14V16C5 18.8284 5 20.2426 5.87868 21.1213C6.75736 22 8.17157 22 11 22H13C15.8284 22 17.2426 22 18.1213 21.1213C19 20.2426 19 18.8284 19 16Z" stroke-linejoin="round"></path>
                    <path d="M19 18C20.4142 18 21.1213 18 21.5607 17.5607C22 17.1213 22 16.4142 22 15C22 13.5858 22 12.8787 21.5607 12.4393C21.1213 12 20.4142 12 19 12" stroke-linejoin="round"></path>
                    <path d="M5 18C3.58579 18 2.87868 18 2.43934 17.5607C2 17.1213 2 16.4142 2 15C2 13.5858 2 12.8787 2.43934 12.4393C2.87868 12 3.58579 12 5 12" stroke-linejoin="round"></path>
                    <path d="M13.5 3.5C13.5 4.32843 12.8284 5 12 5C11.1716 5 10.5 4.32843 10.5 3.5C10.5 2.67157 11.1716 2 12 2C12.8284 2 13.5 2.67157 13.5 3.5Z"></path>
                    <path d="M12 5V8" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M9 13V14" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M15 13V14" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M10 17.5C10 17.5 10.6667 18 12 18C13.3333 18 14 17.5 14 17.5" stroke-linecap="round"></path>
                </svg>
                <span>تامین فلات</span>
            </a>
        </div>

        <nav>
            <a href="?page=dashboard" class="<?= $activePage === 'dashboard' ? 'active' : '' ?>
                <svg xmlns=" http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" color="currentColor" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round">
                <path d="M10.5 8.75V6.75C10.5 5.10626 10.5 4.28439 10.046 3.73121C9.96291 3.62995 9.87005 3.53709 9.76879 3.45398C9.21561 3 8.39374 3 6.75 3C5.10626 3 4.28439 3 3.73121 3.45398C3.62995 3.53709 3.53709 3.62995 3.45398 3.73121C3 4.28439 3 5.10626 3 6.75V8.75C3 10.3937 3 11.2156 3.45398 11.7688C3.53709 11.8701 3.62995 11.9629 3.73121 12.046C4.28439 12.5 5.10626 12.5 6.75 12.5C8.39374 12.5 9.21561 12.5 9.76879 12.046C9.87005 11.9629 9.96291 11.8701 10.046 11.7688C10.5 11.2156 10.5 10.3937 10.5 8.75Z"></path>
                <path d="M7.75 15.5H5.75C5.05222 15.5 4.70333 15.5 4.41943 15.5861C3.78023 15.78 3.28002 16.2802 3.08612 16.9194C3 17.2033 3 17.5522 3 18.25C3 18.9478 3 19.2967 3.08612 19.5806C3.28002 20.2198 3.78023 20.72 4.41943 20.9139C4.70333 21 5.05222 21 5.75 21H7.75C8.44778 21 8.79667 21 9.08057 20.9139C9.71977 20.72 10.22 20.2198 10.4139 19.5806C10.5 19.2967 10.5 18.9478 10.5 18.25C10.5 17.5522 10.5 17.2033 10.4139 16.9194C10.22 16.2802 9.71977 15.78 9.08057 15.5861C8.79667 15.5 8.44778 15.5 7.75 15.5Z"></path>
                <path d="M21 17.25V15.25C21 13.6063 21 12.7844 20.546 12.2312C20.4629 12.1299 20.3701 12.0371 20.2688 11.954C19.7156 11.5 18.8937 11.5 17.25 11.5C15.6063 11.5 14.7844 11.5 14.2312 11.954C14.1299 12.0371 14.0371 12.1299 13.954 12.2312C13.5 12.7844 13.5 13.6063 13.5 15.25V17.25C13.5 18.8937 13.5 19.7156 13.954 20.2688C14.0371 20.3701 14.1299 20.4629 14.2312 20.546C14.7844 21 15.6063 21 17.25 21C18.8937 21 19.7156 21 20.2688 20.546C20.3701 20.4629 20.4629 20.3701 20.546 20.2688C21 19.7156 21 18.8937 21 17.25Z"></path>
                <path d="M18.25 3H16.25C15.5522 3 15.2033 3 14.9194 3.08612C14.2802 3.28002 13.78 3.78023 13.5861 4.41943C13.5 4.70333 13.5 5.05222 13.5 5.75C13.5 6.44778 13.5 6.79667 13.5861 7.08057C13.78 7.71977 14.2802 8.21998 14.9194 8.41388C15.2033 8.5 15.5522 8.5 16.25 8.5H18.25C18.9478 8.5 19.2967 8.5 19.5806 8.41388C20.2198 8.21998 20.72 7.71977 20.9139 7.08057C21 6.79667 21 6.44778 21 5.75C21 5.05222 21 4.70333 20.9139 4.41943C20.72 3.78023 20.2198 3.28002 19.5806 3.08612C19.2967 3 18.9478 3 18.25 3Z"></path>
                </svg>
                داشبورد
            </a>

            <a href="?page=users" class="<?= $activePage === 'users' ? 'active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" color="currentColor" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M13 11C13 8.79086 11.2091 7 9 7C6.79086 7 5 8.79086 5 11C5 13.2091 6.79086 15 9 15C11.2091 15 13 13.2091 13 11Z"></path>
                    <path d="M11.0386 7.55773C11.0131 7.37547 11 7.18927 11 7C11 4.79086 12.7909 3 15 3C17.2091 3 19 4.79086 19 7C19 9.20914 17.2091 11 15 11C14.2554 11 13.5584 10.7966 12.9614 10.4423"></path>
                    <path d="M15 21C15 17.6863 12.3137 15 9 15C5.68629 15 3 17.6863 3 21"></path>
                    <path d="M21 17C21 13.6863 18.3137 11 15 11"></path>
                </svg>
                کاربران
            </a>

            <a href="?page=surveys" class="<?= $activePage === 'surveys' ? 'active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" color="currentColor" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M15 21V6C15 5.06812 15 4.60218 14.8478 4.23463C14.6448 3.74458 14.2554 3.35523 13.7654 3.15224C13.3978 3 12.9319 3 12 3C11.0681 3 10.6022 3 10.2346 3.15224C9.74458 3.35523 9.35523 3.74458 9.15224 4.23463C9 4.60218 9 5.06812 9 6V21H15Z"></path>
                    <path d="M17 8H15V21H17C18.8856 21 19.8284 21 20.4142 20.4142C21 19.8284 21 18.8856 21 17V12C21 10.1144 21 9.17157 20.4142 8.58579C19.8284 8 18.8856 8 17 8Z"></path>
                    <path d="M9 13H7C5.11438 13 4.17157 13 3.58579 13.5858C3 14.1716 3 15.1144 3 17C3 18.8856 3 19.8284 3.58579 20.4142C4.17157 21 5.11438 21 7 21H9V13Z"></path>
                </svg>
                نظرسنجی‌
            </a>

            <a href="?page=routines" class="<?= $activePage === 'routines' ? 'active' : '' ?>">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" color="currentColor" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M13.498 2H8.49805C7.66962 2 6.99805 2.67157 6.99805 3.5C6.99805 4.32843 7.66962 5 8.49805 5H13.498C14.3265 5 14.998 4.32843 14.998 3.5C14.998 2.67157 14.3265 2 13.498 2Z"></path>
                    <path d="M6.99805 15H10.4266M6.99805 11H14.998"></path>
                    <path d="M18.9981 13.5V9.48263C18.9981 6.65424 18.9981 5.24004 18.1194 4.36137C17.4781 3.72007 16.5515 3.54681 14.9981 3.5M11.998 21.9995L8.99805 21.9995C6.16963 21.9995 4.75541 21.9995 3.87674 21.1208C2.99806 20.2421 2.99805 18.8279 2.99805 15.9995L2.99806 9.48269C2.99805 6.65425 2.99805 5.24004 3.87673 4.36136C4.51802 3.72007 5.44456 3.54681 6.99795 3.5"></path>
                    <path d="M13.998 20C13.998 20 14.998 20 15.998 22C15.998 22 18.1745 17 20.998 16"></path>
                </svg>
                روتین‌ های ربات
            </a>
        </nav>

        <div class="sidebar-footer">
            <a href="?logout" class="logout">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16" color="currentColor" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4.39267 4.00087C4 4.61597 4 5.41166 4 7.00304V16.997C4 18.5883 4 19.384 4.39267 19.9991C4.46279 20.109 4.5414 20.2132 4.62777 20.3108C5.11144 20.8572 5.87666 21.0758 7.4071 21.513C8.9414 21.9513 9.70856 22.1704 10.264 21.8417C10.3604 21.7847 10.45 21.7171 10.5313 21.6402C11 21.1965 11 20.3988 11 18.8034V5.19662C11 3.60122 11 2.80351 10.5313 2.35982C10.45 2.28288 10.3604 2.21527 10.264 2.15827C9.70856 1.82956 8.9414 2.0487 7.4071 2.48699C5.87666 2.92418 5.11144 3.14278 4.62777 3.68925C4.5414 3.78684 4.46279 3.89103 4.39267 4.00087Z"></path>
                    <path d="M11 4H13.0171C14.9188 4 15.8696 4 16.4604 4.58579C16.7898 4.91238 16.9355 5.34994 17 6M11 20H13.0171C14.9188 20 15.8696 20 16.4604 19.4142C16.7898 19.0876 16.9355 18.6501 17 18"></path>
                    <path d="M21 12H14M19.5 9.49994C19.5 9.49994 22 11.3412 22 12C22 12.6588 19.5 14.4999 19.5 14.4999"></path>
                </svg>
                خروج
            </a>
        </div>
    </aside>

    <main class="main">

        <?php if ($activePage === 'dashboard'): ?>

            <div class="page-header">
                <h1>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" color="currentColor" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round">
                        <path d="M10.5 8.75V6.75C10.5 5.10626 10.5 4.28439 10.046 3.73121C9.96291 3.62995 9.87005 3.53709 9.76879 3.45398C9.21561 3 8.39374 3 6.75 3C5.10626 3 4.28439 3 3.73121 3.45398C3.62995 3.53709 3.53709 3.62995 3.45398 3.73121C3 4.28439 3 5.10626 3 6.75V8.75C3 10.3937 3 11.2156 3.45398 11.7688C3.53709 11.8701 3.62995 11.9629 3.73121 12.046C4.28439 12.5 5.10626 12.5 6.75 12.5C8.39374 12.5 9.21561 12.5 9.76879 12.046C9.87005 11.9629 9.96291 11.8701 10.046 11.7688C10.5 11.2156 10.5 10.3937 10.5 8.75Z"></path>
                        <path d="M7.75 15.5H5.75C5.05222 15.5 4.70333 15.5 4.41943 15.5861C3.78023 15.78 3.28002 16.2802 3.08612 16.9194C3 17.2033 3 17.5522 3 18.25C3 18.9478 3 19.2967 3.08612 19.5806C3.28002 20.2198 3.78023 20.72 4.41943 20.9139C4.70333 21 5.05222 21 5.75 21H7.75C8.44778 21 8.79667 21 9.08057 20.9139C9.71977 20.72 10.22 20.2198 10.4139 19.5806C10.5 19.2967 10.5 18.9478 10.5 18.25C10.5 17.5522 10.5 17.2033 10.4139 16.9194C10.22 16.2802 9.71977 15.78 9.08057 15.5861C8.79667 15.5 8.44778 15.5 7.75 15.5Z"></path>
                        <path d="M21 17.25V15.25C21 13.6063 21 12.7844 20.546 12.2312C20.4629 12.1299 20.3701 12.0371 20.2688 11.954C19.7156 11.5 18.8937 11.5 17.25 11.5C15.6063 11.5 14.7844 11.5 14.2312 11.954C14.1299 12.0371 14.0371 12.1299 13.954 12.2312C13.5 12.7844 13.5 13.6063 13.5 15.25V17.25C13.5 18.8937 13.5 19.7156 13.954 20.2688C14.0371 20.3701 14.1299 20.4629 14.2312 20.546C14.7844 21 15.6063 21 17.25 21C18.8937 21 19.7156 21 20.2688 20.546C20.3701 20.4629 20.4629 20.3701 20.546 20.2688C21 19.7156 21 18.8937 21 17.25Z"></path>
                        <path d="M18.25 3H16.25C15.5522 3 15.2033 3 14.9194 3.08612C14.2802 3.28002 13.78 3.78023 13.5861 4.41943C13.5 4.70333 13.5 5.05222 13.5 5.75C13.5 6.44778 13.5 6.79667 13.5861 7.08057C13.78 7.71977 14.2802 8.21998 14.9194 8.41388C15.2033 8.5 15.5522 8.5 16.25 8.5H18.25C18.9478 8.5 19.2967 8.5 19.5806 8.41388C20.2198 8.21998 20.72 7.71977 20.9139 7.08057C21 6.79667 21 6.44778 21 5.75C21 5.05222 21 4.70333 20.9139 4.41943C20.72 3.78023 20.2198 3.28002 19.5806 3.08612C19.2967 3 18.9478 3 18.25 3Z"></path>
                    </svg>
                    داشبورد
                </h1>
            </div>

            <div class="grid">
                <div class="stat-card">
                    <div class="num"><?= $users ?></div>
                    <div class="label">کل کاربران</div>
                </div>
                <div class="stat-card">
                    <div class="num"><?= $surveys ?></div>
                    <div class="label">کل نظرات</div>
                </div>
            </div>

        <?php elseif ($activePage === 'users'): ?>

            <div class="page-header">
                <h1>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" color="currentColor" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M13 11C13 8.79086 11.2091 7 9 7C6.79086 7 5 8.79086 5 11C5 13.2091 6.79086 15 9 15C11.2091 15 13 13.2091 13 11Z"></path>
                        <path d="M11.0386 7.55773C11.0131 7.37547 11 7.18927 11 7C11 4.79086 12.7909 3 15 3C17.2091 3 19 4.79086 19 7C19 9.20914 17.2091 11 15 11C14.2554 11 13.5584 10.7966 12.9614 10.4423"></path>
                        <path d="M15 21C15 17.6863 12.3137 15 9 15C5.68629 15 3 17.6863 3 21"></path>
                        <path d="M21 17C21 13.6863 18.3137 11 15 11"></path>
                    </svg>
                    کاربران
                </h1>
            </div>

            <div class="table-card">
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

        <?php elseif ($activePage === 'surveys'): ?>

            <div class="page-header">
                <h1>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" color="currentColor" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M15 21V6C15 5.06812 15 4.60218 14.8478 4.23463C14.6448 3.74458 14.2554 3.35523 13.7654 3.15224C13.3978 3 12.9319 3 12 3C11.0681 3 10.6022 3 10.2346 3.15224C9.74458 3.35523 9.35523 3.74458 9.15224 4.23463C9 4.60218 9 5.06812 9 6V21H15Z"></path>
                        <path d="M17 8H15V21H17C18.8856 21 19.8284 21 20.4142 20.4142C21 19.8284 21 18.8856 21 17V12C21 10.1144 21 9.17157 20.4142 8.58579C19.8284 8 18.8856 8 17 8Z"></path>
                        <path d="M9 13H7C5.11438 13 4.17157 13 3.58579 13.5858C3 14.1716 3 15.1144 3 17C3 18.8856 3 19.8284 3.58579 20.4142C4.17157 21 5.11438 21 7 21H9V13Z"></path>
                    </svg>
                    نظرسنجی
                </h1>
            </div>

            <div class="table-card">
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

        <?php elseif ($activePage === 'routines'): ?>

            <div class="page-header">
                <h1>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" color="currentColor" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M13.498 2H8.49805C7.66962 2 6.99805 2.67157 6.99805 3.5C6.99805 4.32843 7.66962 5 8.49805 5H13.498C14.3265 5 14.998 4.32843 14.998 3.5C14.998 2.67157 14.3265 2 13.498 2Z"></path>
                        <path d="M6.99805 15H10.4266M6.99805 11H14.998"></path>
                        <path d="M18.9981 13.5V9.48263C18.9981 6.65424 18.9981 5.24004 18.1194 4.36137C17.4781 3.72007 16.5515 3.54681 14.9981 3.5M11.998 21.9995L8.99805 21.9995C6.16963 21.9995 4.75541 21.9995 3.87674 21.1208C2.99806 20.2421 2.99805 18.8279 2.99805 15.9995L2.99806 9.48269C2.99805 6.65425 2.99805 5.24004 3.87673 4.36136C4.51802 3.72007 5.44456 3.54681 6.99795 3.5"></path>
                        <path d="M13.998 20C13.998 20 14.998 20 15.998 22C15.998 22 18.1745 17 20.998 16"></path>
                    </svg>
                    روتین‌ های ربات
                </h1>
            </div>

            <?php if ($uploadMessage): ?>
                <div class="alert <?= $uploadMessage['type'] ?>"><?= $uploadMessage['text'] ?></div>
            <?php endif; ?>

            <div class="upload-card">
                <h2>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" color="currentColor" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2.99994 17C2.99994 17.93 2.99994 18.395 3.10216 18.7765C3.37956 19.8117 4.18821 20.6204 5.22348 20.8978C5.60498 21 6.06997 21 6.99994 21L16.9999 21C17.9299 21 18.3949 21 18.7764 20.8978C19.8117 20.6204 20.6203 19.8117 20.8977 18.7765C20.9999 18.395 20.9999 17.93 20.9999 17"></path>
                        <path d="M16.5 7.49993C16.5 7.49993 13.1858 2.99997 12 2.99996C10.8141 2.99995 7.50002 7.49996 7.50002 7.49996M12 3.99996V16"></path>
                    </svg>
                    آپلود فهرست اقلام + قیمت
                </h2>

                <form method="POST" enctype="multipart/form-data">
                    <div class="upload-zone" onclick="document.getElementById('price_pdf').click()">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="40" height="40" color="#94a3b8" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2.99994 17C2.99994 17.93 2.99994 18.395 3.10216 18.7765C3.37956 19.8117 4.18821 20.6204 5.22348 20.8978C5.60498 21 6.06997 21 6.99994 21L16.9999 21C17.9299 21 18.3949 21 18.7764 20.8978C19.8117 20.6204 20.6203 19.8117 20.8977 18.7765C20.9999 18.395 20.9999 17.93 20.9999 17"></path>
                            <path d="M16.5 7.49993C16.5 7.49993 13.1858 2.99997 12 2.99996C10.8141 2.99995 7.50002 7.49996 7.50002 7.49996M12 3.99996V16"></path>
                        </svg>
                        <p id="file-name-label">برای انتخاب فایل کلیک کنید</p>
                        <small>فقط فایل PDF پشتیبانی می‌شود</small>
                    </div>
                    <input type="file" name="price_pdf" id="price_pdf" accept="application/pdf">
                    <button type="submit" class="btn-upload">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" color="currentColor" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M20 15.0057V10.6606C20 9.84276 20 9.43383 19.8478 9.06613C19.6955 8.69843 19.4065 8.40927 18.8284 7.83096L14.0919 3.09236C13.593 2.59325 13.3436 2.3437 13.0345 2.19583C12.9702 2.16508 12.9044 2.13778 12.8372 2.11406C12.5141 2 12.1614 2 11.4558 2C8.21082 2 6.58831 2 5.48933 2.88646C5.26731 3.06554 5.06508 3.26787 4.88607 3.48998C4 4.58943 4 6.21265 4 9.45908V14.0052C4 17.7781 4 19.6645 5.17157 20.8366C6.11466 21.7801 7.52043 21.9641 10 22M13 2.50022V3.00043C13 5.83009 13 7.24492 13.8787 8.12398C14.7574 9.00304 16.1716 9.00304 19 9.00304H19.5"></path>
                            <path d="M15 22C14.3932 21.4102 12 19.8403 12 19C12 18.1597 14.3932 16.5898 15 16M13 19H20"></path>
                        </svg> ایمپورت فایل و بروزرسانی</button>
                </form>
            </div>

        <?php endif; ?>

    </main>

    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const hamburger = document.getElementById('hamburger');
            const overlay = document.getElementById('overlay');

            sidebar.classList.toggle('open');
            hamburger.classList.toggle('open');
            overlay.classList.toggle('show');

            document.body.style.overflow =
                sidebar.classList.contains('open') ? 'hidden' : '';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const sidebar = document.querySelector('.sidebar');
                if (sidebar.classList.contains('open')) toggleSidebar();
            }
        });

        document.getElementById('price_pdf') &&
            document.getElementById('price_pdf').addEventListener('change', function() {
                const label = document.getElementById('file-name-label');
                if (label) label.textContent = this.files[0] ? this.files[0].name : 'برای انتخاب فایل کلیک کنید';
            });
    </script>

</body>

</html>