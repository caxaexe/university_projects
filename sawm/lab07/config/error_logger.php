<?php

function log_error_message($message) {
    $time = date("Y-m-d H:i:s");
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

    $line = "[$time] ip=$ip error=\"$message\"\n";

    file_put_contents(__DIR__ . '/../logs/errors.log', $line, FILE_APPEND);
}

set_error_handler(function($errno, $errstr, $errfile, $errline) {
    $msg = "PHP ERROR: $errstr in $errfile at line $errline";
    log_error_message($msg);

    header("Location: ../handlers/error_safe.php");
    exit();
});

register_shutdown_function(function() {
    $error = error_get_last();
    if ($error) {
        $msg = "FATAL ERROR: {$error['message']} in {$error['file']} at line {$error['line']}";
        log_error_message($msg);

        header("Location: ../handlers/error_safe.php");
        exit();
    }
});
