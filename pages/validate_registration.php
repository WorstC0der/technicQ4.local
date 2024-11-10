<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Access-Control-Allow-Credentials: true');
header('Content-Type: application/json');

ini_set('display_errors', 1);
error_reporting(E_ALL);

include 'db.php'; // Подключение к базе данных

$errors = [];
$email = '';
$password = '';
$confirmPassword = '';

// Функция для проверки email
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Функция для проверки пароля
function validatePassword($password) {
    return preg_match('/^[a-zA-Z0-9!@#$%^&*()_+{}:;<>,.?\\-]{8,}$/', $password);
}

// Проверка уникальности email в базе данных
function isEmailUnique($email, $conn) {
    $stmt = $conn->prepare('SELECT COUNT(*) FROM users WHERE email = ?');
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();
    return $count == 0;
}

try {
    // Проверка предварительного запроса (OPTIONS)
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        http_response_code(200);
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $postData = json_decode(file_get_contents('php://input'), true);
        
        // Получаем данные и обрабатываем их
        $email = trim($postData['registration-email'] ?? '');
        $password = trim($postData['registration-password'] ?? '');
        $confirmPassword = trim($postData['confirm-password'] ?? '');

        // Проверка email
        if (empty($email) || !validateEmail($email)) {
            $errors['registration-email'] = 'Введите корректный e-mail.';
        } elseif (!isEmailUnique($email, $conn)) {
            $errors['registration-email'] = 'Этот e-mail уже используется.';
        }

        // Проверка пароля
        if (empty($password) || !validatePassword($password)) {
            $errors['registration-password'] = 'Пароль должен содержать не менее 8 символов. Допускается ввод символов латинского алфавита, цифр и спецсимволов.';
        }

        // Проверка совпадения паролей
        if ($password !== $confirmPassword) {
            $errors['confirm-password'] = 'Пароли не совпадают.';
        }

        // Если есть ошибки
        if (!empty($errors)) {
            echo json_encode(['errors' => $errors]);
            exit();
        }

        // Хэширование пароля
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Назначаем роль по умолчанию 'user'
        $role = 'user';

        // Вставка нового пользователя в базу данных
        $stmt = $conn->prepare("INSERT INTO users (email, password, role) VALUES (?, ?, ?)");
        $stmt->bind_param('sss', $email, $hashedPassword, $role);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // Регистрация прошла успешно, теперь авторизуем пользователя

            // Получаем информацию о пользователе
            $stmt = $conn->prepare('SELECT password, role FROM users WHERE email = ?');
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $user = $result->fetch_assoc();
                // Проверка пароля
                if (password_verify($password, $user['password'])) {
                    // Сохраняем авторизацию в сессии
                    session_start();
                    $_SESSION['logged_in'] = true;
                    $_SESSION['user_email'] = $email;
                    $_SESSION['user_role'] = $user['role'];

                    $_SESSION['message'] = 'Регистрация прошла успешно и вы вошли в аккаунт!';

                    $redirectUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';

                    echo json_encode([
                        'success' => 'Регистрация прошла успешно и вы вошли в аккаунт!', 
                        'redirect' => $redirectUrl]);
                } else {
                    echo json_encode(['error' => 'Ошибка при авторизации.']);
                }
            } else {
                echo json_encode(['error' => 'Ошибка при регистрации.']);
            }

            $stmt->close();
        } else {
            echo json_encode(['error' => 'Ошибка регистрации. Попробуйте снова.']);
        }

        $conn->close();
        exit();
    }
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
    exit();
}
?>
