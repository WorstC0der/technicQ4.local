<?php
$title = "Административная панель. Управление отзывами";
session_start();

require_once("header.php");
include 'db.php';

// Проверка, что пользователь авторизован и имеет роль администратора
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
  echo "<div class='container'><h1>У вас нет доступа к этой странице.</h1></div>";
  exit();
}

// Запрос к базе данных для получения всех отзывов
$query = "SELECT id, email, rating, comment FROM reviews";
$result = $conn->query($query);
?>

<div class="container">
  <h1 class="title">Управление отзывами</h1>
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
    <button id="add-button" class="admin-button" onclick="openAddModal()">Добавить отзыв</button>
    <table class="table">
      <thead>
        <tr>
          <th>Email</th>
          <th>Оценка</th>
          <th>Отзыв</th>
          <th>Действия</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($review = $result->fetch_assoc()): ?>
          <tr>
            <td><?php echo htmlspecialchars($review['email']); ?></td>
            <td><?php echo htmlspecialchars($review['rating']); ?></td>
            <td><?php echo htmlspecialchars(mb_strimwidth($review['comment'], 0, 50, '...')); ?></td>
            <td>
              <button 
                id="update-button"
                class="admin-button" 
                data-id="<?php echo $review['id']; ?>" 
                data-rating="<?php echo htmlspecialchars($review['rating']); ?>"
                data-comment="<?php echo htmlspecialchars($review['comment']); ?>">
                Редактировать
              </button>
              <button 
                id="delete-button"
                class="admin-button" 
                data-id="<?php echo $review['id']; ?>"
                data-email="<?php echo htmlspecialchars($review['email']); ?>"
                data-rating="<?php echo htmlspecialchars($review['rating']); ?>"
                data-comment="<?php echo htmlspecialchars($review['comment']); ?>">
                Удалить
              </button>
            </td>
          </tr>
          <?php endwhile; ?>
        </tbody>
    </table>
  </div>

<!-- Модальное окно для редактирования отзыва -->
<div id="updateModal" class="modal">
  <div class="modal-content modal-content-admin-reviews">
    <div class="close-container">
      <span class="close" onclick="closeUpdateModal()">&times;</span>
    </div>
    <h2>Редактировать отзыв</h2>
    <form id="updateForm" method="POST" action="updateReview.php" class="modal-content-form">
      <input type="hidden" name="id" id="updateId" value="">

      <label for="updateRating">Оценка</label>
      <select name="rating" id="updateRating" required>
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
        <option value="4">4</option>
        <option value="5">5</option>
      </select>

      <label for="updateComment">Отзыв</label>
      <textarea name="comment" id="updateComment" rows="10" required class="modal-content-textarea"></textarea>
      <button type="submit" class="admin-button">Редактировать отзыв</button>
    </form>
  </div>
</div>

  <!-- Модальное окно для подтверждения удаления отзыва -->
  <div id="deleteModal" class="modal">
    <div class="modal-content modal-content-admin">
      <div class="close-container">
        <span class="close" onclick="closeDeleteModal()">&times;</span>
      </div>
      <h2>Подтвердите удаление</h2>
      <p>Вы уверены, что хотите удалить этот отзыв?</p>
      <p><strong>Email:</strong> <span id="deleteEmail"></span></p>
      <p><strong>Оценка:</strong> <span id="deleteRating"></span></p>
      <p><strong>Отзыв:</strong> <span id="deleteComment"></span></p>
      <form id="deleteForm" method="POST" action="deleteReview.php">
        <input type="hidden" name="id" id="deleteId">
        <button type="submit" class="admin-button">Удалить</button>
        <button type="button" class="admin-button" onclick="closeDeleteModal()">Отмена</button>
      </form>
    </div>
  </div>

      <!-- Модальное окно для добавления нового отзыва -->
  <div id="addModal" class="modal">
    <div class="modal-content modal-content-admin">
        <div class="close-container">
          <span class="close" onclick="closeAddModal()">&times;</span>
        </div>
        <h2>Добавить новый отзыв</h2>
        <form id="addForm" method="POST" action="addReview.php" class="modal-content-form">
          <label for="addEmail">Почта</label>
          <input type="email" name="email" id="addEmail" required class="modal-content-input" />
          <label for="addRating">Оценка</label>
          <select name="rating" id="addRating" required>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
            <option value="5">5</option>
          </select>

          <label for="addComment">Отзыв</label>
          <textarea name="comment" id="addComment" rows="10" required class="modal-content-textarea"></textarea>
          <button type="submit" class="admin-button">Добавить отзыв</button>
        </form>
    </div>
  </div>
</div>

<script>
  document.querySelectorAll('[id^="update-button"]').forEach(button => {
    button.addEventListener('click', function() {
      const id = this.getAttribute('data-id');
      const rating = this.getAttribute('data-rating');
      const comment = this.getAttribute('data-comment');

      openUpdateModal(id, rating, comment);
    });
  });

  document.querySelectorAll('[id^="delete-button"]').forEach(button => {
    button.addEventListener('click', function() {
      const id = this.getAttribute('data-id');
      const email = this.getAttribute('data-email');
      const rating = this.getAttribute('data-rating');
      const comment = this.getAttribute('data-comment');

      openDeleteModal(id, email, rating, comment);
    });
  });

  document.querySelectorAll('[id^="add-button"]').forEach(button => {
    button.addEventListener('click', function() {
      const email = this.getAttribute('data-email');
      const rating = this.getAttribute('data-rating');
      const comment = this.getAttribute('data-comment');

      openAddModal(email, rating, comment);
    });
  });

  function openUpdateModal(id, rating, comment) {
    document.getElementById('updateId').value = id;
    document.getElementById('updateRating').value = rating;
    document.getElementById('updateComment').textContent = comment;
    document.getElementById('updateModal').style.display = 'block';
  }

  function openDeleteModal(id, email, rating, comment) {
    document.getElementById('deleteId').value = id;
    document.getElementById('deleteEmail').textContent = email;
    document.getElementById('deleteRating').value = rating;
    document.getElementById('deleteComment').textContent = comment;
    document.getElementById('deleteModal').style.display = 'block';
  }

  function openAddModal() {
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
