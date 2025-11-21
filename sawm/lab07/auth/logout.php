<?php

session_start();
session_destroy();

header("Location: login.php");

require_once __DIR__ . '/../config/error_logger.php';
require_once __DIR__ . '/../config/logger.php';

log_action("Выход из системы");

