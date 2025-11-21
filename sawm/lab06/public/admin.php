<?php
session_start();

require_once __DIR__ . '/../config/logger.php';
log_action("Открыта страница admin.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    log_action("Попытка доступа без прав (не admin)");
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Админски</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="card">
        <h2>Добро пожаловать, <?php echo $_SESSION['login']; ?>!</h2>
        <p>Это защищённая страница администратора. Тут можно нажать на кнопочку "Выйти" по-админски.</p>

        <a class="logout-link" href="logout.php">Выйти</a>

        <hr>
        <h3>Просмотр лог-файла</h3>
        <a class="logout-link" href="view_logs.php">Посмотреть журнал действий</a>
    </div>
</body>
</html>
