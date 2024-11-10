<?php
session_start();
include 'db.php';

// Проверка, что пользователь авторизован и имеет роль администратора
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "<div class='container'><h1>У вас нет доступа к этой странице.</h1></div>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];

    if (isset($id) && is_numeric($id)) {
        $stmt = $conn->prepare("DELETE FROM pages WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}

$conn->close();
header("Location: pages.php");
exit();
?>
