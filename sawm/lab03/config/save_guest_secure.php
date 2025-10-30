<?php
session_start();
require_once __DIR__ . '/bd.php';


$user = trim($_POST['user'] ?? '');
$email = trim($_POST['e_mail'] ?? null);
$msg  = trim($_POST['text_message'] ?? '');

if ($user === '' || $msg === '') {
    die('Заполните все обязательные поля.');
}


if (mb_strlen($user,'UTF-8') > 100 || mb_strlen($msg,'UTF-8') > 5000) {
    die('Слишком длинное сообщение.');
}

$stmt = $pdo->prepare("INSERT INTO guest (user, text_message, e_mail) VALUES (:user, :msg, :email)");
$stmt->execute([
    ':user' => $user,
    ':msg' => $msg,
    ':email' => $email
]);

header("Location: ../public/guest.php");
exit;
