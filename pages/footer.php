<?php
    include 'db.php';

    // Получаем контент для хедера
    $headerContent = '';
    $query = "SELECT title, content FROM pages WHERE page = 'footer'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $headerContent = $row['content'];
    }

    // Сохраняем контент хедера во временный файл и подключаем его
    $headerFile = 'temp_header.php';
    file_put_contents($headerFile, $headerContent);
    include $headerFile;
    unlink($headerFile); // Удаляем временный файл после подключения
?>