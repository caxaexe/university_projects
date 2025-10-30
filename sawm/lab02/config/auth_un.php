<?php
$conn = new mysqli("localhost", "root", "", "sawm");

if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

$login = $_POST['login'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE login = '$login' AND password = '$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    session_start();
    $_SESSION['admin'] = $login;
    header("Location: ../public/admin.php");
}

$conn->close();
