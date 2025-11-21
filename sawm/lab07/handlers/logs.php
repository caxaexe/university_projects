<?php
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

require_once __DIR__ . '/../config/logger.php';
require_once __DIR__ . '/../config/error_logger.php';

$file = __DIR__ . "/../logs/errors.log";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear'])) {
    file_put_contents($file, "");
    $message = "Лог ошибок успешно очищен!";
}
?>


<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../assets/css/style.css">
    <title>Error logs</title>
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

    <div class="log-actions">
        <form method="post">
            <button class="btn btn-small" name="clear" value="1">Очистить лог</button>
        </form>
    </div>

    <a class="logout-link" href="../dashboards/admin.php">Назад</a>
</div>

</body>
</html>
