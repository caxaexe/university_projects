    <?php

    session_start();
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
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
    <body>
        <div class="card">
            <h2>Добро пожаловать, <?php echo $_SESSION['login']; ?>!</h2>
            <p>Это защищённая страница администратора. Тут можно нажать на кнопочку "Выйти" по-админски.</p>
            <a class="logout-link" href="logout.php">Выйти</a>
        </div>
    </body>
    </html>
