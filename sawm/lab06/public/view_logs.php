<?php
session_start();
require_once __DIR__ . '/../config/logger.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    log_action("Попытка просмотра логов без прав");
    header("Location: login.php");
    exit();
}

log_action("Открыт просмотр журналов");

$logFile = __DIR__ . '/../logs/actions.log';

$logs = file_exists($logFile) ? file_get_contents($logFile) : "Файл журнала пуст.";
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Журнал действий</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<div class="card">
    <h2>Журнал действий пользователей</h2>

    <pre style="background:#111; color:#0f0; padding:10px; max-height:500px; overflow:auto;">
<?= htmlspecialchars($logs) ?>
    </pre>

    <a class="logout-link" href="admin.php">Назад</a>
</div>
</body>
</html>
