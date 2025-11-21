<?php
session_start();

require_once __DIR__ . '/../config/logger.php';
log_action("Открыта панель менеджера");

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "sawm");
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM users WHERE id = $id AND role = 'user'");
    header("Location: manager.php");
    exit();
}

$result = $conn->query("SELECT id, login, role FROM users WHERE role = 'user'");
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Менеджерски</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="manager-wrapper">
        <div class="manager-header">
            <h2>Добро пожаловать, <?= htmlspecialchars($_SESSION['login']) ?>!</h2>
            <p>Это защищённая страница менджера. Тут можно просматривать инфу про юзеров и менять ее, а еще нажать на кнопочку "Выйти" по-менеджерски.</p>
        </div>

        <table class="manager-table">
            <tr>
                <th>ID</th>
                <th>Логин</th>
                <th>Роль</th>
                <th></th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) : ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['login']) ?></td>
                <td><?= htmlspecialchars($row['role']) ?></td>
                <td class="manager-actions">
                    <a class="edit" href="manager_edit.php?id=<?= $row['id'] ?>">Изменить</a>
                    <a class="delete" href="manager.php?delete=<?= $row['id'] ?>" onclick="return confirm('Удалить пользователя?')">Удалить</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>

        <div>
            <a class="logout-link" href="logout.php">Выйти</a>
        </div>
    </div>
</body>
</html>
