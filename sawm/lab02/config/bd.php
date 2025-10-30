<?php
$dsn = 'mysql:host=localhost;dbname=sawm;charset=utf8mb4';
$user = 'user';
$pass = 'password';

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Подключение не удалось: " . $e->getMessage());
}
