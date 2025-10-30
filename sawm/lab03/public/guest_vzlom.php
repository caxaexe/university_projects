<?php

session_start();
require_once __DIR__ . '/../config/bd.php';

$stmt = $pdo->query("SELECT id, user, text_message, e_mail, data_time_message FROM guest ORDER BY id DESC");
$rows = $stmt->fetchAll();
?>

<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8" />
  <title>Записки</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>

<div class="guest-wrapper">

  <div class="login-container">
      <h2>Гостевая книга</h2>
      <form action="../config/save_guest_vzlom.php" method="post">
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
              <strong class="guest-username"><?= $r['user'] ?></strong>
              <span class="guest-time"><?= $r['data_time_message'] ?></span>
              <?php if (!empty($r['e_mail'])): ?>
                <div class="guest-email">
                  <a href="mailto:<?= $r['e_mail'] ?>">
                    <?= $r['e_mail'] ?>
                  </a>
                </div>
              <?php endif; ?>
            </div>
            <div class="guest-message">
              <?= $r['text_message'] ?>
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
