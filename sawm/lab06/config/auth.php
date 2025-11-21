<?php

session_start();

require_once __DIR__ . '/logger.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../public/login.php");
    exit();
}

$login = trim($_POST['login'] ?? '');
$password = $_POST['password'] ?? '';
$error = '';

log_action("Попытка входа: login=$login");

if ($login === '' || $password === '') {
    $error = "Введите логин и пароль.";
    log_action("Ошибка: не введены логин или пароль");
} else {
    $conn = new mysqli("localhost", "root", "", "sawm");
    if ($conn->connect_error) {
        log_action("Ошибка подключения к БД при входе");
        die("Ошибка подключения: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id, login, password, role FROM users WHERE login = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $hash = $user['password'];
        $ok = false;

        if (str_starts_with($hash, '$argon2id$') || str_starts_with($hash, '$2y$')) {
            $ok = password_verify($password, $hash);
        } else {
            if ($password === $hash) {
                $ok = true;
                $newHash = password_hash($password, PASSWORD_ARGON2ID);
                $upd = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $upd->bind_param("si", $newHash, $user['id']);
                $upd->execute();
                $upd->close();
            }
        }
        if ($ok) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['login'] = $user['login'];
            $_SESSION['role'] = $user['role'];

             log_action("Успешный вход: login={$user['login']} role={$user['role']}");

            switch ($user['role']) {
                case 'admin':
                    header("Location: ../public/admin.php");
                    break;
                case 'manager':
                    header("Location: ../public/manager.php");
                    break;
                default:
                    header("Location: ../public/user.php");
                    break;
            }
            exit();
        } else {
            $error = "Неверный логин или пароль.";
            log_action("Неуспешная попытка входа: неправильный пароль для login=$login");
        }
    } else {
        $error = "Пользователь не найден.";
        log_action("Неуспешная попытка входа: login=$login не найден");
    }

    $stmt->close();
    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Ошибка авторизации</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="error-box">
        <p class="error-message"><?= htmlspecialchars($error ?: 'Неверный логин или пароль') ?></p>
        <p class="info">Проверьте данные и попробуйте снова.</p>
        <a href="../public/login.php" class="back-link">Назад</a>
    </div>
</body>
</html>
