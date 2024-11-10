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

    // Запрос для получения блоков с полем title
    $query = "SELECT id, page, title, content FROM pages";
    $result = $conn->query($query);
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.js"></script>

<div class="container">
    <h1 class="title">Управление текстовыми страницами</h1>
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
        <button id="add-button" class="admin-button" onclick="openAddModal()">Добавить страницу</button>
        <table class="table">
            <thead>
                <tr>
                    <th>Страница</th>
                    <th>Название</th>
                    <th>Контент</th>
                    <th>Действие</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($block = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($block['page']); ?></td>
                    <td><?php echo htmlspecialchars($block['title']); ?></td>
                    <td><?php echo htmlspecialchars(mb_strimwidth($block['content'], 0, 50, '...')); ?></td>
                    <td>
                        <!-- Кнопка редактирования -->
                        <button 
                            id="update-button" 
                            class="admin-button" 
                            data-id="<?php echo $block['id']; ?>" 
                            data-page="<?php echo htmlspecialchars($block['page']); ?>" 
                            data-title="<?php echo htmlspecialchars($block['title']); ?>"
                            data-content="<?php echo htmlspecialchars($block['content']); ?>">
                            Редактировать
                        </button>
                        <!-- Кнопка удаления -->
                        <button 
                            id="delete-button" 
                            class="admin-button" 
                            data-id="<?php echo $block['id']; ?>"
                            data-page="<?php echo htmlspecialchars($block['page']); ?>" 
                            data-title="<?php echo htmlspecialchars($block['title']); ?>"
                            data-content="<?php echo htmlspecialchars($block['content']); ?>">
                            Удалить
                        </button>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <!-- Модальное окно редактирования -->
    <div id="updateModal" class="modal">
        <div class="modal-content modal-content-admin-pages">
            <div class="close-container">
                <span class="close" onclick="closeUpdateModal()">&times;</span>
            </div>
            <h2>Редактировать блок</h2>
            <form id="editForm" method="POST" action="updateBlock.php" class="modal-content-form">

                <input type="hidden" name="id" id="blockId">
                <input type="hidden" name="hiddenContent" id="hiddenContent">

                <label for="blockPage">Страница</label>
                <input type="text" name="page" id="page" required>

                <label for="blockTitle">Название</label>
                <input type="text" name="title" id="title" required>

                <label for="blockContent">Контент</label>
                <!-- div для ACE Editor вместо textarea -->
                <div name="content" id="content" style="height: 300px;"></div>

                <button type="submit" class="admin-button">Сохранить изменения</button>
            </form>
        </div>
    </div>

    <!-- Модальное окно удаления -->
    <div id="deleteModal" class="modal">
        <div class="modal-content modal-content-admin-pages">
            <div class="close-container">
                <span class="close" onclick="closeDeleteModal()">&times;</span>
            </div>
            <h2>Вы точно хотите удалить данный блок?</h2>
            <form id="editForm" method="POST" action="deleteBlock.php" class="modal-content-form">

                <input type="hidden" name="id" id="blockIdDelete">
                <input type="hidden" name="hiddenContent" id="hiddenContent">

                <label for="blockPage">Страница</label>
                <input type="text" name="page" id="pageDelete" required>

                <label for="blockTitle">Название</label>
                <input type="text" name="title" id="titleDelete" required>

                <label for="blockContent">Контент</label>
                <!-- div для ACE Editor вместо textarea -->
                <div name="deleteContent" id="deleteContent" style="height: 300px;"></div>

                <button type="submit" class="admin-button">Удалить</button>
            </form>
        </div>
    </div>

    <!-- Модальное окно добавления -->
    <div id="addModal" class="modal">
        <div class="modal-content modal-content-admin-pages">
            <div class="close-container">
                <span class="close" onclick="closeAddModal()">&times;</span>
            </div>
            <h2>Добавить блок</h2>
            <form id="addForm" method="POST" action="addBlock.php" class="modal-content-form">

                <input type="hidden" name="id" id="blockId">
                <input type="hidden" name="hiddenContent" id="hiddenContent">

                <label for="blockPage">Страница</label>
                <input type="text" name="page" id="page" required>

                <label for="blockTitle">Название</label>
                <input type="text" name="title" id="title" required>

                <label for="blockContent">Контент</label>
                <!-- div для ACE Editor вместо textarea -->
                <div name="addContent" id="addContent" style="height: 300px;"></div>

                <button type="submit" class="admin-button">Сохранить изменения</button>
            </form>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('[id^="update-button"]').forEach(button => {
        button.addEventListener('click', function() {
            // Получаем данные из data-атрибутов
            const id = this.getAttribute('data-id');
            const page = this.getAttribute('data-page');
            const title = this.getAttribute('data-title');
            const content = this.getAttribute('data-content');

            openUpdateModal(id, page, title, content);
        });
    });

    document.querySelectorAll('[id^="delete-button"]').forEach(button => {
    button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const page = this.getAttribute('data-page');
            const title = this.getAttribute('data-title');
            const content = this.getAttribute('data-content');
            
            openDeleteModal(id, page, title, content);
        });
    });

    document.querySelectorAll('[id^="add-button"]').forEach(button => {
        button.addEventListener('click', function() {
            openAddModal();
        });
    });

    function openUpdateModal(id, page, title, content) {
        document.getElementById('blockId').value = id;
        document.getElementById('page').value = page;
        document.getElementById('title').value = title;

        // Инициализация ACE Editor с PHP, HTML и JS
        const editor = ace.edit("content");
        editor.setTheme("ace/theme/monokai");
        editor.session.setMode("ace/mode/php");

        // Установим текущее содержимое в редактор
        editor.setValue(content, -1);

        // Перед отправкой формы обновляем скрытое поле с содержимым из Ace Editor
        document.getElementById('editForm').onsubmit = function() {
            // Обновляем скрытое поле контента перед отправкой
            document.getElementById('hiddenContent').value = editor.getValue();
        };

        document.getElementById('updateModal').style.display = 'block';
    }

    function openDeleteModal(id, page, title, content) {
        document.getElementById('blockIdDelete').value = id;
        document.getElementById('pageDelete').value = page;
        document.getElementById('titleDelete').value = title;

        // Инициализация ACE Editor с PHP, HTML и JS
        const editor = ace.edit("deleteContent");
        editor.setTheme("ace/theme/monokai");
        editor.session.setMode("ace/mode/php");

        // Установим текущее содержимое в редактор
        editor.setValue(content, -1);

        // Убедимся, что скрытое поле контента перед отправкой будет обновлено
        document.getElementById('editForm').onsubmit = function() {
            document.getElementById('hiddenContent').value = editor.getValue();
        };

        // Открываем модальное окно
        document.getElementById('deleteModal').style.display = 'block';
    }


    function openAddModal() {
        // Устанавливаем значения для новой страницы
        document.getElementById('blockId').value = '';
        document.getElementById('page').value = '';
        document.getElementById('title').value = '';
        
        // Инициализация ACE Editor
        const editor = ace.edit("addContent");
        editor.setTheme("ace/theme/monokai");
        editor.session.setMode("ace/mode/php");


        // Обновляем скрытое поле с содержимым перед отправкой
        document.getElementById('addForm').onsubmit = function() {
            document.getElementById('hiddenContent').value = editor.getValue();
        };

        document.getElementById('addModal').style.display = 'block';
    }


    function closeUpdateModal() {
        document.getElementById('updateModal').style.display = 'none';
    }

    function closeDeleteModal() {
        document.getElementById('deleteModal').style.display = 'none';
    }

    function closeAddModal() {
        document.getElementById('addModal').style.display = 'none';
    }
</script>
<?php
    $conn->close();
?>
