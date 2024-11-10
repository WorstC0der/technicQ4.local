<?php
// Настройки подключения к базе данных
$host = 'MySQL-8.0';
$dbName = 'tecnicQ';
$dbUser = 'root';
$dbPassword = '';

// Подключение к базе данных
$conn = new mysqli($host, $dbUser, $dbPassword, $dbName);

// Проверка подключения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}
?>
