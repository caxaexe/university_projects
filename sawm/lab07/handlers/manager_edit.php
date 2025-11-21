<?php

session_start();

require_once __DIR__ . '/../config/logger.php';
require_once __DIR__ . '/../config/error_logger.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'manager') {
    log_action("Попытка входа в edit.php без прав менеджера");
    header("Location: ../auth/login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "sawm");
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    die("Некорректный ID");
}

$user = $conn->query("SELECT * FROM users WHERE id = $id")->fetch_assoc();

if (!$user) {
    die("Пользователь не найден.");
}

if ($user['role'] === 'manager' || $user['role'] === 'admin') {
    die("Вы не можете редактировать этого пользователя.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);

    if (!empty($login)) {
        $stmt = $conn->prepare("UPDATE users SET login = ? WHERE id = ?");
        $stmt->bind_param("si", $login, $id);
        $stmt->execute();
        $stmt->close();

        log_action("Менеджер изменил данные пользователя ID = $id (новый логин: $login)");
    }

    header("Location: ../dashboards/manager.php");
    exit();
}

log_action("Открыта страница редактирования пользователя ID=$id");

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактирование пользователя</title>
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="container">
    <h2>Лог ошибок</h2>

    <?php if (!empty($message)): ?>
        <p style="color: green;"><b><?= htmlspecialchars($message) ?></b></p>
    <?php endif; ?>

    <?php
    if (file_exists($file)) {
        $content = file_get_contents($file);

        if (trim($content) === "") {
            echo "<p>Лог пуст.</p>";
        } else {
            echo "<pre>" . htmlspecialchars($content) . "</pre>";
        }
    } else {
        echo "<p>Файл лога не найден.</p>";
    }
    ?>

    <form method="post">
        <button class="btn-clear" name="clear" value="1">Очистить лог</button>
    </form>
</div>

<a class="logout-link logout-bottom" href="../dashboards/admin.php">Назад</a>

</body>
</html>
