<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../public/login.php");
    exit();
}

$login = trim($_POST['login'] ?? '');
$password = $_POST['password'] ?? '';
$error = '';

if ($login === '' || $password === '') {
    $error = "Введите логин и пароль.";
} else {
    $conn = new mysqli("localhost", "root", "", "sawm");
    if ($conn->connect_error) {
        die("Ошибка подключения: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id, login, password FROM users WHERE login = ?");
    $stmt->bind_param("s", $login);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $hash = $user['password'];
        $ok = false;

        // проверка является ли пароль хэшем
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
            if (mb_strtolower($user['login']) === 'admin') {
                $_SESSION['admin'] = $user['login'];
                header("Location: ../public/admin.php");
            } else {
                $_SESSION['user'] = $user['login'];
                header("Location: ../public/user.php");
            }
            exit();
        } else {
            $error = "Неверный логин или пароль.";
        }
    } else {
        $error = "Пользователь не найден.";
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
