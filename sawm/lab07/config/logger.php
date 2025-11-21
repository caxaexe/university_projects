<?php

require_once __DIR__ . '/../config/error_logger.php';

function log_action($action) {
    
    $user = $_SESSION['login'] ?? 'guest';
    $role = $_SESSION['role'] ?? 'unknown';
    $ip   = $_SERVER['REMOTE_ADDR'];
    $time = date("Y-m-d H:i:s");

    $line = "[$time] user=$user role=$role ip=$ip action=\"$action\"\n";

    file_put_contents(__DIR__ . '/../logs/actions.log', $line, FILE_APPEND);
}
