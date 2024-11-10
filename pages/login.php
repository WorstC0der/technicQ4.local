<?php
session_start();

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
} else {
    $message = null;
}
?>

<div class="user" id="user">
    <img src="/img/user.svg" alt="user" />
    
    <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
        <p><?= htmlspecialchars($_SESSION['user_email']) ?></p>
        <div id="modal" class="modal">
            <div class="logout">
                <div class="modal-content">
                    <b>Здравствуйте, <?= htmlspecialchars($_SESSION['user_email']) ?></b>
                    <div class="close-container">
                        <span class="close">&times;</span>
                    </div>
                    
                    <!-- Проверяем, является ли пользователь администратором -->
                    <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                        <a href="admin.php" style="color: white">
                            <button type="button" class="login-button">
                                Перейти в админку
                            </button>
                        </a>
                    <?php endif; ?>

                    <a href="validate_logout.php" style="color: white">
                        <button type="button" class="login-button" id="confirmLogout">
                            Выход   
                        </button>
                    </a>
                </div>
            </div>
        </div>
    <?php else: ?>
        <!-- Если пользователь не вошел, показ формы входа -->
        <p>Войти</p>
        <div id="modal" class="modal">
            <div class="login">
                <div class="modal-content">
                    <b>Войти<br />или зарегистрироваться</b>
                    <div class="close-container">
                        <span class="close">&times;</span>
                    </div>
                    <form id="loginForm" class="loginForm" method="POST" action="validate_login.php">
                        <!-- Форма входа -->
                        <div class="input-container <?= isset($errors['login-email']) ? 'error' : '' ?>">
                            <input
                                type="text"
                                id="login-email"
                                name="login-email"
                                placeholder="e-mail"
                                value="<?= isset($_POST['login-email']) ? htmlspecialchars($_POST['login-email']) : '' ?>"
                                oninput="toLowerCase(this)"
                            />
                        </div>
                        <span class="error-message" id="login-email-error-message">
                            <?= isset($errors['login-email']) ? htmlspecialchars($errors['login-email']) : '' ?>
                        </span>

                        <div class="input-container <?= isset($errors['login-password']) ? 'error' : '' ?>">
                            <input
                                type="password"
                                id="login-password"
                                name="login-password"
                                placeholder="Пароль"
                            />
                            <button
                                id="login-togglePassword"
                                type="button"
                                class="toggle-password-button"
                            >
                                <i class="fa fa-eye-slash" style="font-size: 20px"></i>
                            </button>
                        </div>
                        <span class="error-message" id="login-password-error-message">
                            <?= isset($errors['login-password']) ? htmlspecialchars($errors['login-password']) : '' ?>
                        </span>

                        <div class="sign-in">
                            <p>Впервые на сайте? <a href="#" id="show-registration">Зарегистрируйтесь</a></p>
                        </div>

                        <button type="submit" class="login-button">
                            Войти
                        </button>
                    </form>
                </div>
            </div>

            <div class="registration">
                <div class="modal-content">
                    <b>Зарегистрироваться<br />или войти</b>
                    <div class="close-container">
                        <span class="close">&times;</span>
                    </div>
                    <form id="registrationForm" class="loginForm" method="POST" action="validate_registration.php">
                        <!-- Форма регистрации -->
                        <div class="input-container <?= isset($errors['registration-email']) ? 'error' : '' ?>">
                            <input
                                type="text"
                                id="registration-email"
                                name="registration-email"
                                placeholder="e-mail"
                                value="<?= isset($_POST['registration-email']) ? htmlspecialchars($_POST['registration-email']) : '' ?>"
                                oninput="toLowerCase(this)"
                            />
                        </div>
                        <span class="error-message" id="registration-email-error-message">
                            <?= isset($errors['registration-email']) ? htmlspecialchars($errors['registration-email']) : '' ?>
                        </span>

                        <div class="input-container <?= isset($errors['registration-password']) ? 'error' : '' ?>">
                            <input
                                type="password"
                                id="registration-password"
                                name="registration-password"
                                placeholder="Пароль"
                            />
                            <button
                                id="registration-togglePassword"
                                type="button"
                                class="toggle-password-button"
                            >
                                <i class="fa fa-eye-slash" style="font-size: 20px"></i>
                            </button>
                        </div>
                        <span class="error-message" id="registration-password-error-message">
                            <?= isset($errors['registration-password']) ? htmlspecialchars($errors['registration-password']) : '' ?>
                        </span>

                        <div class="input-container <?= isset($errors['confirm-password']) ? 'error' : '' ?>">
                            <input
                                type="password"
                                id="confirm-password"
                                name="confirm-password"
                                placeholder="Подтвердите пароль"
                            />
                            <button
                                id="confirm-togglePassword"
                                type="button"
                                class="toggle-password-button"
                            >
                                <i class="fa fa-eye-slash" style="font-size: 20px"></i>
                            </button>
                        </div>
                        <span class="error-message" id="confirm-password-error-message">
                            <?= isset($errors['confirm-password']) ? htmlspecialchars($errors['confirm-password']) : '' ?>
                        </span>

                        <div class="sign-in">
                            <p>Уже зарегистрированы? <a href="#" id="show-login">Войти</a></p>
                        </div>

                        <button type="submit" class="login-button">
                            Зарегистрироваться
                        </button>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php if (isset($_SESSION['message'])): ?>
    <div class="notification">
        <p><?= htmlspecialchars($_SESSION['message']) ?></p>
    </div>
    <?php unset($_SESSION['message']); // Удаляем сообщение после его отображения ?>
<?php endif; ?>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        <?php if ($message): ?>
            const message = <?= json_encode($message) ?>;
            showNotification(message);
        <?php endif; ?>
    });

    function showNotification(message) {
        const notification = document.createElement("div");
        notification.classList.add("notification", "success"); // Добавляем классы для стиля
        notification.textContent = message; // Устанавливаем текст уведомления

        document.body.appendChild(notification); // Добавляем уведомление в документ
        notification.classList.add("show"); // Показываем уведомление

        setTimeout(() => notification.remove(), 3000); // Удаляем уведомление через 3 секунды
    }
</script>

<script src="../scripts/login.js" defer></script>
