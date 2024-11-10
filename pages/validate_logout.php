<?php
include 'db.php';
session_start();
session_unset();

$redirectUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
header("location: $redirectUrl");

$_SESSION['message'] = 'Вы вышли из аккаунта!';
?>