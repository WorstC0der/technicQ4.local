<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $rating = $_POST['rating'];
    $comment = $_POST['comment'];

    $stmt = $conn->prepare("INSERT INTO reviews (email, rating, comment) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $email, $rating, $comment);
    if ($stmt->execute()) {
        header("Location: adminReviews.php");
    } else {
        echo "Ошибка при добавлении отзыва.";
    }
}
?>
