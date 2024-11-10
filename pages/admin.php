<?php
$title = "Административная панель";
session_start();

require_once("header.php");
include 'db.php';

// Проверка, что пользователь авторизован и имеет роль администратора
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "<div class='container'><h1>У вас нет доступа к этой странице.</h1></div>";
    exit();
}
?>

<div class="container">
    <h1 class="title">Административная панель</h1>
</div>
<div class="container">
    <div class="sidebar">
        <ul>
          <li>
            <a href="users.php">
                <img src="../img/user.svg" class="pic" />
                Управление пользователями
            </a>
          </li>
          <li>
            <a href="pages.php">
                <img src="../img/index/computer.svg" class="pic" />
                Управление текстовыми страницами
            </a>
          </li>
          <li>
            <a href="adminReviews.php">
                <img src="../img/index/computer.svg" class="pic" />
                Управление отзывами
            </a>
          </li>
        </ul>
      </div>
    </a>
    <div class="main-content">
    </div>
</div>
