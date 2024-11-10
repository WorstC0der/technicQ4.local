<?php
    include 'db.php';

    $query = "SELECT title, content FROM pages WHERE page = 'index'";
    $result = $conn->query($query);

    $content = '';
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $title = $row['title'];
        $content = $row['content'];
    }
    $conn->close();
    
    require_once("header.php");

    // Сохраняем контент во временный файл и подключаем его
    $tempFile = 'temp_index.php';
    file_put_contents($tempFile, $content);
    include $tempFile;
    unlink($tempFile); // Удаляем временный файл после подключения  
?>

<script src="../scripts/index.js"></script>
<?php require_once("footer.php"); ?>
