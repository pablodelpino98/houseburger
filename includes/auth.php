<?php
session_start();

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function setLanguageCookie($lang) {
    setcookie('lang', $lang, time() + (86400 * 30), "/"); // 30 días
}

if(isset($_GET['lang'])) {
    setLanguageCookie($_GET['lang']);
    header('Location: '.$_SERVER['PHP_SELF']);
    exit();
}

$currentLang = 'es';
if(isset($_COOKIE['lang'])) {
    $currentLang = $_COOKIE['lang'];
}
require_once __DIR__ . "/../lang/$currentLang.php";
?>