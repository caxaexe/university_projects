<?php
session_start();

if (isset($_SESSION['admin'])) {
    header("Location: ../public/admin.php");
    exit();
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../public/login.php");
    exit();
}

$login = trim($_POST['login'] ?? '');
$password = $_POST['password'] ?? '';


if (!preg_match('/^[A-Za-z0-9._-]{3,50}$/', $login)) {
    $error = "Неверный формат логина.";
} else {
    $conn = new mysqli("localhost", "root", "", "sawm");
    if ($conn->connect_error) {
        error_log("DB connect error: " . $conn->connect_error);
        $error = "Ошибка сервера. Попробуйте позже.";
    } else {
        $stmt = $conn->prepare("SELECT id, login, password FROM `users` WHERE login = ?");
        if ($stmt === false) {
            error_log("Prepare failed: " . $conn->error);
            $error = "Ошибка сервера. Попробуйте позже.";
        } else {
            $stmt->bind_param("s", $login);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result && $result->num_rows > 0) {
                $user = $result->fetch_assoc();

                if ($password === $user['password'] && mb_strtolower($user['login']) === 'admin') {
                    $_SESSION['admin'] = $user['login'];
                    $stmt->close();
                    $conn->close();
                    header("Location: ../public/admin.php");
                    exit();
                } else {
                    $error = "Неверный логин или пароль.";
                }
            } else {
                $error = "Неверный логин или пароль.";
            }

            $stmt->close();
        }

        $conn->close();
    }
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
