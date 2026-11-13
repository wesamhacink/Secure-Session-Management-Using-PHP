<?php

require_once 'config.php';

// تسجيل الدخول
if (isset($_POST['login'])) {

    // التحقق من CSRF Token
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        die("Invalid CSRF Token");
    }

    // التحقق من بيانات الدخول التجريبية
    if ($_POST['username'] === 'admin' && $_POST['password'] === '12345') {

        // تجديد معرف الجلسة لمنع Session Fixation
        session_regenerate_id(true);

        $_SESSION['logged_in'] = true;
        $_SESSION['username'] = 'admin';
    } else {
        $error = "Username or Password is incorrect";
    }
}

// إنشاء كوكي محمية
setcookie('secure_cookie', 'Protected Cookie', [
    'expires' => time() + 3600,
    'path' => '/',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Strict'
]);

// إنشاء كوكي غير محمية للمقارنة
setcookie('normal_cookie', 'Normal Cookie', [
    'expires' => time() + 3600,
    'path' => '/',
    'secure' => false,
    'httponly' => false,
    'samesite' => 'Lax'
]);

// قراءة الكوكيز التي وصلت إلى الخادم
$secureCookie = $_COOKIE['secure_cookie'] ?? 'Not received';
$normalCookie = $_COOKIE['normal_cookie'] ?? 'Not received';

?>

<!DOCTYPE html>
<html>
<head>
    <title>Secure Session Management</title>
</head>
<body>

<h2>Secure Session Management</h2>

<?php if (!isset($_SESSION['logged_in'])): ?>

    <h3>Login</h3>

    <?php if (isset($error)): ?>
        <p style="color:red;">
            <?= htmlspecialchars($error) ?>
        </p>
    <?php endif; ?>

    <form method="POST">

        <input type="hidden" name="csrf_token"
               value="<?= htmlspecialchars($_SESSION['csrf_token']) ?>">

        <label>Username:</label>
        <input type="text" name="username" required>

        <br><br>

        <label>Password:</label>
        <input type="password" name="password" required>

        <br><br>

        <button type="submit" name="login">Login</button>

    </form>

<?php else: ?>

    <h3>Welcome <?= htmlspecialchars($_SESSION['username']) ?></h3>

    <p>Session ID:</p>
    <p><?= htmlspecialchars(session_id()) ?></p>

    <h3>Cookie Security Test</h3>

    <p>Secure Cookie: <?= htmlspecialchars($secureCookie) ?></p>

    <p>Normal Cookie: <?= htmlspecialchars($normalCookie) ?></p>

    <h3>JavaScript Cookie Test</h3>

    <p id="cookieTest"></p>

    <script>
        document.getElementById('cookieTest').innerText =
            "document.cookie: " + document.cookie;
    </script>

    <br>

    <a href="logout.php">Logout</a>

<?php endif; ?>

</body>
</html>
