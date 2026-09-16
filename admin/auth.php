<?php
require_once __DIR__ . '/../api/config.php';

session_start();

function require_login() {
    if (empty($_SESSION['admin_id'])) {
        header('Location: login.php');
        exit;
    }
}

function current_admin_username() {
    return $_SESSION['admin_username'] ?? '';
}
