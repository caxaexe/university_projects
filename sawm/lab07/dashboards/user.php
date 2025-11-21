<?php

session_start();

require_once __DIR__ . '/../config/error_logger.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'user') {
    header("Location: ../auth/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Юзерски</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="card">
        <h2>Добро пожаловать, <?php echo htmlspecialchars($_SESSION['login']); ?>!</h2>
        <p>Это защищённая страница пользователя. Тут можно нажать на кнопочку "Выйти" по-юзерски.</p>
        <a class="logout-link" href="../auth/logout.php">Выйти</a>
    </div>
</body>
</html>
