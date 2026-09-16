<?php
require_once __DIR__ . '/../api/config.php';

$pdo = get_db();
$existing = $pdo->query('SELECT COUNT(*) AS c FROM admin_users')->fetch();

if ($existing['c'] > 0) {
    die('Setup already completed. An admin account exists. Delete admin/setup.php from the server for security.');
}

$error = '';
$done = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Username and password are required.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters.';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match.';
    } else {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $pdo->prepare('INSERT INTO admin_users (username, password_hash) VALUES (?, ?)');
        $stmt->execute([$username, $hash]);
        $done = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Setup</title>
<style>
  body{font-family:system-ui,sans-serif;background:#faf9f7;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;}
  .card{background:#fff;padding:40px;border-radius:14px;box-shadow:0 20px 50px -20px rgba(0,0,0,0.25);max-width:380px;width:100%;}
  h1{font-size:1.3rem;margin:0 0 20px;}
  label{display:block;font-weight:600;font-size:0.85rem;margin-bottom:6px;}
  input{width:100%;padding:11px 12px;margin-bottom:16px;border:1.5px solid #e6e2dc;border-radius:8px;font-size:0.95rem;box-sizing:border-box;}
  button{width:100%;padding:13px;background:#e8590c;color:#fff;border:none;border-radius:8px;font-weight:700;font-size:0.95rem;cursor:pointer;}
  .error{background:#fde8e8;color:#a12626;padding:10px 14px;border-radius:8px;margin-bottom:16px;font-size:0.88rem;}
  .success{background:#e6f6ea;color:#1a7a3c;padding:16px;border-radius:8px;font-size:0.9rem;line-height:1.5;}
</style>
</head>
<body>
<div class="card">
  <h1>Create Admin Account</h1>
  <?php if ($done): ?>
    <div class="success">
      Admin account created successfully.<br><br>
      <strong>Now delete this file (admin/setup.php) from the server</strong> so nobody else can access it, then <a href="login.php">log in here</a>.
    </div>
  <?php else: ?>
    <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
    <form method="POST">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" required>
      <label for="password">Password</label>
      <input type="password" id="password" name="password" minlength="8" required>
      <label for="confirm">Confirm Password</label>
      <input type="password" id="confirm" name="confirm" minlength="8" required>
      <button type="submit">Create Account</button>
    </form>
  <?php endif; ?>
</div>
</body>
</html>
