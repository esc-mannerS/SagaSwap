<?php
session_start();
require_once 'config.php';
require_once 'BookService.php'; // <-- include the API class

// sanitize input
function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Form values
    $user_id = $_SESSION['user_id'];
    $category_id = (int)$_POST['category_id'];
    $isbn_input = sanitize($_POST['isbn']);
    $price_input = sanitize(trim($_POST['price']));
    // inputs masks
    $price_input = str_replace('.', '', $price_input); // remove thousands separator
    $price_input = str_replace(',', '.', $price_input); // decimal to dot
    $isbn_input = preg_replace('/\D+/', '', $isbn_input); // cleaning

    // --- API call: fetch book metadata ---
    if (!empty($isbn_input)) {
        $bookId = BookService::getOrCreateByIsbn($conn, $isbn_input);
        // Optionally, you could use $bookId if you wanted to link it
        // But since you said not to touch insertion, we just call it
    }

    // get municipality_id from users table
    $stmt = $conn->prepare(
        "SELECT municipality_id FROM users WHERE id = ?"
    );
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($municipality_id);
    $stmt->fetch();
    $stmt->close();
    if (!empty($isbn_input)) {
    $bookId = BookService::getOrCreateByIsbn($conn, $isbn_input);
    if (!$bookId) {
        die("Error: Could not create or find book with ISBN $isbn_input");
    }
}

    // now safe to insert listing
    $stmt = $conn->prepare(
        "INSERT INTO listings (user_id, category_id, municipality_id, isbn, price)
        VALUES (?, ?, ?, ?, ?)"
    );

    // insert listing including municipality_id
    $stmt = $conn->prepare(
        "INSERT INTO listings (user_id, category_id, municipality_id, isbn, price)
         VALUES (?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "iiisd",
        $user_id,
        $category_id,
        $municipality_id,
        $isbn_input,
        $price_input
    );

    $stmt->execute();
    $stmt->close();

    // redirect
    header("Location: ../pages/my-profile.php");
    exit;
}