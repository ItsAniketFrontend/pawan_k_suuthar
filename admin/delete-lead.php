<?php
require_once __DIR__ . '/auth.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id'])) {
    $pdo = get_db();
    $stmt = $pdo->prepare('DELETE FROM leads WHERE id = ?');
    $stmt->execute([(int) $_POST['id']]);
}

header('Location: index.php');
exit;
