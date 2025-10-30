<?php
session_start();
require_once __DIR__ . '/bd.php';

$user = $_POST['user'] ?? '';
$email = $_POST['e_mail'] ?? '';
$msg  = $_POST['text_message'] ?? '';

if (empty($user) || empty($msg)) {
    die('Заполните все поля.');
}

$sql = "INSERT INTO guest (user, text_message, e_mail) VALUES ("
     . $pdo->quote($user) . ", "
     . $pdo->quote($msg) . ", "
     . $pdo->quote($email) . ")";
$pdo->exec($sql);

header("Location: ../public/guest_vzlom.php");
exit;
