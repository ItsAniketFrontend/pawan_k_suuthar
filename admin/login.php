<?php
require_once __DIR__ . '/../api/config.php';
session_start();

if (!empty($_SESSION['admin_id'])) {
    header('Location: index.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    $pdo = get_db();
    $stmt = $pdo->prepare('SELECT * FROM admin_users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_username'] = $user['username'];
        header('Location: index.php');
        exit;
    }

    $error = 'Invalid username or password.';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Login | Pawan K. Suuthar</title>
<style>
  body{font-family:system-ui,sans-serif;background:#161311;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0;}
  .card{background:#fff;padding:40px;border-radius:14px;box-shadow:0 20px 50px -20px rgba(0,0,0,0.5);max-width:360px;width:100%;}
  h1{font-size:1.3rem;margin:0 0 4px;}
  p.sub{color:#6b6459;font-size:0.85rem;margin:0 0 24px;}
  label{display:block;font-weight:600;font-size:0.85rem;margin-bottom:6px;}
  input{width:100%;padding:11px 12px;margin-bottom:16px;border:1.5px solid #e6e2dc;border-radius:8px;font-size:0.95rem;box-sizing:border-box;}
  button{width:100%;padding:13px;background:#e8590c;color:#fff;border:none;border-radius:8px;font-weight:700;font-size:0.95rem;cursor:pointer;}
  .error{background:#fde8e8;color:#a12626;padding:10px 14px;border-radius:8px;margin-bottom:16px;font-size:0.88rem;}
</style>
</head>
<body>
<div class="card">
  <h1>Admin Login</h1>
  <p class="sub">Enquiry dashboard</p>
  <?php if ($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <form method="POST">
    <label for="username">Username</label>
    <input type="text" id="username" name="username" required autofocus>
    <label for="password">Password</label>
    <input type="password" id="password" name="password" required>
    <button type="submit">Log In</button>
  </form>
</div>
</body>
</html>
