<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_delete_id'])) {
    $user_id = $_POST['user_delete_id'];

    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Пользователь успешно удален!';
        header("Location: users.php");
        exit();
    } else {
        $_SESSION['message'] = 'Ошибка при удалении пользователя.';
        header("Location: users.php");
        exit();
    }

    $stmt->close();
}

$conn->close();
?>
