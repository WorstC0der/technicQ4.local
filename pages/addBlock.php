<?php
session_start();
include 'db.php';

// Проверка, что пользователь авторизован и имеет роль администратора
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "<div class='container'><h1>У вас нет доступа к этой странице.</h1></div>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $page = $_POST['page'];
    $title = $_POST['title'];
    $content = $_POST['hiddenContent'];

    $stmt = $conn->prepare("INSERT INTO pages (page, title, content) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $page, $title, $content);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
header("Location: pages.php");
exit();

?>
