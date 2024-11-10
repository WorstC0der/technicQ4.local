<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['new_role'])) {
    $user_id = $_POST['user_id'];
    $new_role = $_POST['new_role'];
    
    $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->bind_param("si", $new_role, $user_id);

    if ($stmt->execute()) {
        $_SESSION['message'] = 'Пользователь успешно редактирован!';
        header("Location: users.php");
        exit();
    } else {
        $_SESSION['message'] = 'Ошибка при редактировании пользователя.';
        header("Location: users.php");
        exit();
    }
}

$conn->close();
?>
