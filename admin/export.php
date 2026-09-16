<?php
require_once __DIR__ . '/auth.php';
require_login();

$pdo = get_db();
$rows = $pdo->query('SELECT id, name, phone, city, apartment_type, source, created_at FROM leads ORDER BY created_at DESC')->fetchAll();

$filename = 'leads-' . date('Y-m-d_His') . '.csv';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// UTF-8 BOM so Excel opens it correctly
echo "\xEF\xBB\xBF";

$out = fopen('php://output', 'w');
fputcsv($out, ['ID', 'Name', 'Phone', 'City', 'Apartment Type', 'Source', 'Submitted At']);

foreach ($rows as $row) {
    fputcsv($out, [
        $row['id'],
        $row['name'],
        $row['phone'],
        $row['city'],
        $row['apartment_type'],
        $row['source'],
        $row['created_at'],
    ]);
}

fclose($out);
exit;
