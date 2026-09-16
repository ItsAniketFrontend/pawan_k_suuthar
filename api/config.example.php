<?php
// Copy this file to config.php and fill in your real cPanel MySQL credentials.
// config.php is gitignored and must never be committed with real values.
define('DB_HOST', 'localhost');
define('DB_NAME', 'u694812033_landing');
define('DB_USER', 'u694812033_landing');
define('DB_PASS', 'REPLACE_WITH_YOUR_DB_PASSWORD');

function get_db() {
    static $pdo = null;
    if ($pdo === null) {
        $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $pdo = new PDO($dsn, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
    return $pdo;
}
