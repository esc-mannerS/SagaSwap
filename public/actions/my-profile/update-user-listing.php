<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config.php';

header('Content-Type: application/json');

// check user login
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Ikke logget ind']);
    exit;
}

$user_id = (int)$_SESSION['user_id'];

// read JSON input
$data = json_decode(file_get_contents("php://input"), true);
$listing_id = isset($data['listing_id']) ? (int)$data['listing_id'] : 0;
$price      = isset($data['price']) ? (float)$data['price'] : 0;

// validate input
if ($listing_id <= 0 || $price <= 0) {
    echo json_encode(['success' => false, 'error' => 'Ugyldige data']);
    exit;
}

// update listing (only if it belongs to the user)
$stmt = $conn->prepare("UPDATE listings SET price = ? WHERE id = ? AND user_id = ?");
$stmt->bind_param("dii", $price, $listing_id, $user_id);
$stmt->execute();
$stmt->close();
$conn->close();

// return success (even if price didn’t change)
echo json_encode([
    'success' => true,
    'formatted_price' => number_format($price, 2, ',', '.') . ' DKK'
]);