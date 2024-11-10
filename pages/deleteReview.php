<?php
session_start();
include 'db.php';

// Проверка, что пользователь авторизован и имеет роль администратора
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "<div class='container'><h1>У вас нет доступа к этой странице.</h1></div>";
    exit();
}

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    $stmt = $conn->prepare("DELETE FROM reviews WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Отзыв успешно удален!";
    } else {
        $_SESSION['message'] = "Произошла ошибка при удалении отзыва.";
    }

    $stmt->close();
}

$conn->close();

header("Location: adminReviews.php");
exit();
?>
