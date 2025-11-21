<?php

session_start();
require_once __DIR__ . '/../config/bd.php';
require_once __DIR__ . '/../config/logger.php';

log_action("Гость открыл guest.php");

$stmt = $pdo->query("SELECT id, user, text_message, e_mail, data_time_message FROM guest ORDER BY id DESC");
$rows = $stmt->fetchAll();
?>

<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8" />
  <title>Записки</title>
  <link rel="stylesheet" href="../css/style.css">
  <meta http-equiv="Content-Security-Policy" content="default-src 'self';">
</head>
<body>

<div class="guest-wrapper">

  <div class="login-container">
      <h2>Гостевая книга</h2>
      <form action="../config/save_guest.php" method="post">
          <label>Имя:</label>
          <input type="text" name="user" required>

          <label>E-mail:</label>
          <input type="email" name="e_mail" required>

          <label>Сообщение:</label>
          <textarea name="text_message" rows="6" required></textarea>

          <input type="submit" value="Отправить">
      </form>

      <div class="nav-buttons">
          <a href="../index.php">На главную</a>
      </div>
  </div>

  <div class="guest-section">
    <h2 class="guest-subtitle">Записи</h2>
    <?php if (empty($rows)): ?>
      <p class="guest-empty">Записей пока нет.</p>
    <?php else: ?>
      <div class="guest-entries">
        <?php foreach ($rows as $r): ?>
          <div class="guest-entry-card">
            <div class="guest-meta">
              <strong class="guest-username"><?= htmlspecialchars($r['user'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?></strong>
              <span class="guest-time"><?= htmlspecialchars($r['data_time_message']) ?></span>
              <?php if (!empty($r['e_mail']) && filter_var($r['e_mail'], FILTER_VALIDATE_EMAIL)): ?>
                <div class="guest-email">
                  <a href="mailto:<?= htmlspecialchars($r['e_mail'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>">
                    <?= htmlspecialchars($r['e_mail'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') ?>
                  </a>
                </div>
              <?php endif; ?>
            </div>
            <div class="guest-message">
              <?= nl2br(htmlspecialchars($r['text_message'], ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8')) ?>
            </div>
          </div>
          <hr class="guest-separator">
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>

</div>

</body>
</html>