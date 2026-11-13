<?php

// إعداد خصائص كوكي الجلسة قبل بدء الجلسة
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Strict'
]);

// بدء الجلسة
session_start();

// إنشاء CSRF Token إذا لم يكن موجوداً
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
