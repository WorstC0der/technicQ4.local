<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');

session_start();

include 'db.php'; // Подключение к базе данных

$errors = [];
$email = '';
$password = '';

// Функция для проверки email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Функция для проверки пароля
function validatePassword($password) {
    return preg_match('/^[a-zA-Z0-9!@#$%^&*()_+{}:;<>,.?\\-]{8,}$/', $password);
}

try {
    // Проверка предварительного запроса (OPTIONS)
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $postData = json_decode(file_get_contents('php://input'), true);

        // Обработка входа
        $email = trim($postData['login-email'] ?? '');
        $password = trim($postData['login-password'] ?? '');

        // Проверка email
        if (empty($email) || !validateEmail($email)) {
            $errors['login-email'] = 'Введите корректный e-mail.';
        }

        // Проверка пароля
        if (empty($password) || !validatePassword($password)) {
            $errors['login-password'] = 'Пароль должен содержать не менее 8 символов. Допускается ввод символов латинского алфавита, цифр и спецсимволов.';
        }

        // Если есть ошибки
        if (!empty($errors)) {
            echo json_encode(['errors' => $errors]);
            exit();
        }

        // Проверка существующего пользователя в базе данных
        $stmt = $conn->prepare('SELECT password, role FROM users WHERE email = ?');
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Проверка успешного входа
            if (password_verify($password, $user['password'])) {
                // Сохраняем авторизацию в сессии
                $_SESSION['logged_in'] = true;
                $_SESSION['user_email'] = $email;
                $_SESSION['user_role'] = $user['role'];
                
                $_SESSION['message'] = 'Успешный вход!';

                $redirectUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';

                echo json_encode([
                    'success' => 'Успешный вход!',
                    'redirect' => $redirectUrl
                ]);
            } else {
                echo json_encode(['errors' => ['login-password' => 'Неверный пароль.']]);
            }
        } else {
            echo json_encode(['errors' => ['login-email' => 'Пользователь с таким e-mail не найден.']]);
        }

        $stmt->close();
        $conn->close();
        exit();
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
    exit();
}

echo json_encode(['error' => 'Неверный метод запроса.']);
exit();
