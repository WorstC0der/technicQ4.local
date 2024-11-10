<?php
$title = "Административная панель. Пользователи";
session_start();

require_once("header.php");
include 'db.php';

// Проверка, что пользователь авторизован и имеет роль администратора
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    echo "<div class='container'><h1>У вас нет доступа к этой странице.</h1></div>";
    exit();
}

// Запрос к базе данных для получения всех пользователей
$query = "SELECT id, email, role FROM users";
$result = $conn->query($query);
?>

<div class="container">
    <h1 class="title">Управление пользователями</h1>
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

    <div class="main-content">
        <?php if ($result->num_rows > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Email</th>
                        <th>Роль</th>
                        <th>Действие</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($user['id']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td>
                            <td>
                                <!-- Кнопка редактирования -->
                                <button 
                                    id="update-button"
                                    class="admin-button" 
                                    onclick="openUpdateModal(<?php echo $user['id']; ?>, '<?php echo $user['role']; ?>', 
                                            '<?php echo $user['email']; ?>')">Редактировать
                                </button>

                                <!-- Кнопка удаления -->
                                <button 
                                    id="delete-button"  
                                    class="admin-button delete-btn" 
                                    onclick="openDeleteModal(<?php echo $user['id']; ?>, '<?php echo $user['role']; ?>', 
                                            '<?php echo $user['email']; ?>')">Удалить
                                </button>

                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Пользователи не найдены.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Модальное окно для редактирования -->
<div id="updateModal" class="modal">
    <div class="modal-content modal-content-admin-users">
        <div class="close-container">
            <span class="close" onclick="closeUpdateModal()">&times;</span>
        </div>
        <h2>Редактировать роль пользователя: <span id="user_email"></span></h2>
        <form class="modal-content-form" method="post" action="updateRole.php">
            <input type="hidden" name="user_id" id="user_id">
            <label for="new_role">Роль:</label>
            <select name="new_role" id="new_role">
                <option value="user">user</option>
                <option value="admin">admin</option>
            </select>
            <button type="submit" class="admin-button">Сохранить</button>
        </form>
    </div>
</div>

<!-- Модальное окно для удаления -->
<div id="deleteModal" class="modal">
    <div class="modal-content modal-content-admin-users">
        <div class="close-container">
            <span class="close" onclick="closeDeleteModal()">&times;</span>
        </div>
        <h2>Вы действительно хотите удалить пользователя <span id="user_delete_email"></span>?</h2>
        <form class="modal-content-form" method="post" action="deleteRole.php">
            <input type="hidden" name="user_delete_id" id="user_delete_id">
            <p>Роль <span id="user_role"></span></p>
            <button type="submit" class="admin-button">Удалить</button>
        </form>
    </div>
</div>
<script>
    function openUpdateModal(userId, currentRole, userEmail) {
        document.getElementById("user_id").value = userId;
        document.getElementById("new_role").value = currentRole;
        document.getElementById("user_email").textContent = userEmail;
        document.getElementById("updateModal").style.display = "flex";
    }

    function openDeleteModal(userId, currentRole, userEmail) {
        document.getElementById("user_delete_id").value = userId;
        document.getElementById("user_role").textContent = currentRole;
        document.getElementById("user_delete_email").textContent = userEmail;
        document.getElementById("deleteModal").style.display = "flex";
    }

    function closeUpdateModal() {
        document.getElementById("updateModal").style.display = "none";
    }

    function closeDeleteModal() {
        document.getElementById("deleteModal").style.display = "none";
    }
</script>
<?php 
    $conn->close();
?>