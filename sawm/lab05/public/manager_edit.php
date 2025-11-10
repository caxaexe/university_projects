<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "sawm");
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

$id = intval($_GET['id']);
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
    }
    header("Location: manager.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Редакт</title>
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
