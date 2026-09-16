<?php
require_once __DIR__ . '/auth.php';
require_login();

$pdo = get_db();

$search = trim($_GET['q'] ?? '');
$params = [];
$where = '';
if ($search !== '') {
    $where = 'WHERE name LIKE ? OR phone LIKE ? OR city LIKE ?';
    $like = '%' . $search . '%';
    $params = [$like, $like, $like];
}

$stmt = $pdo->prepare("SELECT * FROM leads $where ORDER BY created_at DESC");
$stmt->execute($params);
$leads = $stmt->fetchAll();

$total = count($leads);
$today = date('Y-m-d');
$todayCount = count(array_filter($leads, fn($l) => substr($l['created_at'], 0, 10) === $today));
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Enquiries | Admin</title>
<style>
  :root{--orange:#e8590c;--ink:#161311;--line:#e6e2dc;--paper:#faf9f7;}
  *{box-sizing:border-box;}
  body{font-family:system-ui,sans-serif;background:var(--paper);color:var(--ink);margin:0;}
  .topbar{background:var(--ink);color:#fff;padding:16px 24px;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;}
  .topbar h1{font-size:1.1rem;margin:0;}
  .topbar a{color:#cfc9c2;text-decoration:none;font-size:0.85rem;font-weight:600;}
  .topbar a:hover{color:#fff;}
  .wrap{max-width:1100px;margin:0 auto;padding:24px;}
  .stats{display:flex;gap:16px;margin-bottom:20px;flex-wrap:wrap;}
  .stat{background:#fff;border-radius:10px;padding:16px 22px;box-shadow:0 1px 0 var(--line);}
  .stat strong{display:block;font-size:1.6rem;color:var(--orange);}
  .stat span{font-size:0.8rem;color:#6b6459;font-weight:600;}
  .toolbar{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;gap:12px;flex-wrap:wrap;}
  .toolbar form{display:flex;gap:8px;}
  input[type=text]{padding:10px 12px;border:1.5px solid var(--line);border-radius:8px;font-size:0.9rem;min-width:220px;}
  .btn{display:inline-flex;align-items:center;gap:6px;padding:10px 16px;border-radius:8px;font-weight:700;font-size:0.85rem;text-decoration:none;border:none;cursor:pointer;}
  .btn-primary{background:var(--orange);color:#fff;}
  .btn-outline{background:#fff;color:var(--ink);border:1.5px solid var(--line);}
  .btn-danger{background:#fde8e8;color:#a12626;padding:6px 12px;font-size:0.78rem;}
  table{width:100%;border-collapse:collapse;background:#fff;border-radius:10px;overflow:hidden;box-shadow:0 1px 0 var(--line);}
  th,td{padding:12px 14px;text-align:left;font-size:0.86rem;border-bottom:1px solid var(--line);}
  th{background:#f1efec;font-weight:700;font-size:0.78rem;text-transform:uppercase;letter-spacing:0.03em;color:#6b6459;}
  tr:last-child td{border-bottom:none;}
  .empty{text-align:center;padding:60px 20px;color:#8a8377;}
  .badge{display:inline-block;padding:3px 8px;border-radius:6px;background:#f1efec;font-size:0.75rem;font-weight:600;}
</style>
</head>
<body>
<div class="topbar">
  <h1>Enquiries Dashboard</h1>
  <div>
    <span style="margin-right:16px;color:#cfc9c2;font-size:0.85rem;">Logged in as <?= htmlspecialchars(current_admin_username()) ?></span>
    <a href="logout.php">Log Out</a>
  </div>
</div>

<div class="wrap">
  <div class="stats">
    <div class="stat"><strong><?= $total ?></strong><span>Total Enquiries</span></div>
    <div class="stat"><strong><?= $todayCount ?></strong><span>Today</span></div>
  </div>

  <div class="toolbar">
    <form method="GET">
      <input type="text" name="q" placeholder="Search name, phone or city" value="<?= htmlspecialchars($search) ?>">
      <button type="submit" class="btn btn-outline">Search</button>
      <?php if ($search): ?><a href="index.php" class="btn btn-outline">Clear</a><?php endif; ?>
    </form>
    <a href="export.php" class="btn btn-primary">Export to Excel (CSV)</a>
  </div>

  <?php if (empty($leads)): ?>
    <div class="empty">No enquiries found<?= $search ? ' for "' . htmlspecialchars($search) . '"' : ' yet' ?>.</div>
  <?php else: ?>
    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Phone</th>
          <th>City</th>
          <th>Apartment</th>
          <th>Source</th>
          <th>Date</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($leads as $lead): ?>
        <tr>
          <td><?= htmlspecialchars($lead['name']) ?></td>
          <td><a href="tel:<?= htmlspecialchars($lead['phone']) ?>"><?= htmlspecialchars($lead['phone']) ?></a></td>
          <td><?= htmlspecialchars($lead['city']) ?></td>
          <td><span class="badge"><?= htmlspecialchars($lead['apartment_type']) ?></span></td>
          <td><?= htmlspecialchars($lead['source']) ?></td>
          <td><?= htmlspecialchars(date('d M Y, h:i A', strtotime($lead['created_at']))) ?></td>
          <td>
            <form method="POST" action="delete-lead.php" onsubmit="return confirm('Delete this enquiry? This cannot be undone.');" style="display:inline;">
              <input type="hidden" name="id" value="<?= (int) $lead['id'] ?>">
              <button type="submit" class="btn btn-danger">Delete</button>
            </form>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</div>
</body>
</html>
