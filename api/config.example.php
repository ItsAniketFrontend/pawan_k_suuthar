<?php
// Copy this file to config.php and fill in your real cPanel MySQL and
// email credentials. config.php is gitignored and must never be
// committed with real values.
define('DB_HOST', 'localhost');
define('DB_NAME', 'u694812033_landing');
define('DB_USER', 'u694812033_landing');
define('DB_PASS', 'REPLACE_WITH_YOUR_DB_PASSWORD');

// SMTP settings for lead notification emails, sent via the
// info@kumbhinteriors.com Hostinger mailbox. Confirm host/port/encryption
// in hPanel > Emails > info@kumbhinteriors.com > Configure Email Client
// if smtp.hostinger.com on port 465 (SSL) does not work.
define('SMTP_HOST', 'smtp.hostinger.com');
define('SMTP_PORT', 465);
define('SMTP_ENCRYPTION', 'ssl'); // 'ssl' for port 465, 'tls' for port 587
define('SMTP_USER', 'info@kumbhinteriors.com');
define('SMTP_PASS', 'REPLACE_WITH_YOUR_MAILBOX_PASSWORD');
define('SMTP_FROM', 'info@kumbhinteriors.com');
define('SMTP_FROM_NAME', 'Pawan K. Suuthar Website');
define('LEAD_NOTIFY_EMAILS', ['kumbhinteriors@gmail.com', 'info@kumbhinteriors.com']);

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
