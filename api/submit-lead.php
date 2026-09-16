<?php
require_once __DIR__ . '/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'error' => 'Method not allowed']);
    exit;
}

function field($key) {
    return isset($_POST[$key]) ? trim($_POST[$key]) : '';
}

$name = field('Name');
$phone = field('Phone');
$city = field('City');
$apartmentType = field('ApartmentType');
$source = field('source') ?: 'hero_form';
$honeypot = field('website');
$redirect = field('_next');

if ($honeypot !== '') {
    // Bot filled the hidden field; pretend success without saving.
    if ($redirect) {
        header('Location: ' . $redirect);
        exit;
    }
    echo json_encode(['ok' => true]);
    exit;
}

if ($name === '' || $phone === '' || $city === '' || $apartmentType === '') {
    http_response_code(422);
    echo json_encode(['ok' => false, 'error' => 'Missing required fields']);
    exit;
}

if (!preg_match('/^[0-9]{10}$/', $phone)) {
    http_response_code(422);
    echo json_encode(['ok' => false, 'error' => 'Phone must be a 10-digit number']);
    exit;
}

try {
    $pdo = get_db();
    $stmt = $pdo->prepare(
        'INSERT INTO leads (name, phone, city, apartment_type, source) VALUES (?, ?, ?, ?, ?)'
    );
    $stmt->execute([$name, $phone, $city, $apartmentType, $source]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['ok' => false, 'error' => 'Could not save enquiry']);
    exit;
}

if ($redirect) {
    header('Location: ' . $redirect);
    exit;
}

echo json_encode(['ok' => true]);
