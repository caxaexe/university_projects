<?php
session_start();

require_once __DIR__ . '/../config/logger.php';

// Проверка роли
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'manager') {
    log_action("Попытка входа в edit.php без прав менеджера");
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "sawm");
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

// Получаем ID пользователя
$id = intval($_GET['id'] ?? 0);
if ($id <= 0) {
    die("Некорректный ID");
}

// Загружаем пользователя
$user = $conn->query("SELECT * FROM users WHERE id = $id")->fetch_assoc();

if (!$user) {
    die("Пользователь не найден.");
}

// Защита — менеджер не может менять менеджеров и админов
if ($user['role'] === 'manager' || $user['role'] === 'admin') {
    die("Вы не можете редактировать этого пользователя.");
}

// Обработка формы
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);

    if (!empty($login)) {
        $stmt = $conn->prepare("UPDATE users SET login = ? WHERE id = ?");
        $stmt->bind_param("si", $login, $id);
        $stmt->execute();
        $stmt->close();

        // ЛОГИРУЕМ факт изменения
        log_action("Менеджер изменил данные пользователя ID=$id (новый логин: $login)");
    }

    header("Location: manager.php");
    exit();
}

// Логируем просто открытие страницы
log_action("Открыта страница редактирования пользователя ID=$id");

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редактирование пользователя</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="login-container">
        <h2>Редактирование пользователя #<?= $user['id'] ?></h2>

        <form method="POST">
            <label>Логин:<br>
                <input type="text" name="login" value="<?= htmlspecialchars($user['login']) ?>" required>
            </label><br>

            <label>Роль:<br>
                <input type="text" value="<?= htmlspecialchars($user['role']) ?>" disabled>
            </label><br>

            <button type="submit" class="btn btn-primary">Сохранить</button>
        </form>

        <a href="manager.php" class="btn">Назад</a>
    </div>
</body>
</html>
