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
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    $stmt = $conn->prepare("UPDATE reviews SET rating = ?, comment = ? WHERE id = ?");
    $stmt->bind_param("isi", $rating, $comment, $id);
    
    if ($stmt->execute()) {
        $_SESSION['message'] = "Отзыв успешно обновлен!";
    } else {
        $_SESSION['message'] = "Произошла ошибка при обновлении отзыва.";
    }

    $stmt->close();
}

$conn->close();

header("Location: adminReviews.php");
exit();
?>
