
<?php

require_once 'config.php';

// حذف بيانات الجلسة
$_SESSION = [];

// حذف كوكي الجلسة
if (ini_get('session.use_cookies')) {

    $params = session_get_cookie_params();

    setcookie(session_name(), '', [
        'expires' => time() - 42000,
        'path' => $params['path'],
        'secure' => $params['secure'],
        'httponly' => $params['httponly'],
        'samesite' => $params['samesite']
    ]);
}

// إنهاء الجلسة
session_destroy();

// العودة إلى الصفحة الرئيسية
header('Location: index.php');
exit;

?>
