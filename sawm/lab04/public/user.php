<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Пользовательская страница</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="card">
        <h2>Добро пожаловать, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h2>
        <p>Это защищённая страница пользователя. Тут можно нажать на кнопочку "Выйти" по-юзерски.</p>
        <a class="link" href="logout.php">Выйти</a>
    </div>
</body>
</html>
