<?php
session_start();
require_once 'config.php';

// sanitize input
function sanitize($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Form values
    $user_id = $_SESSION['user_id'];
    $category_id = (int)$_POST['category_id'];
    $iso_input = sanitize(trim($_POST['iso']));
    $price_input = sanitize(trim($_POST['price']));
    // inputs masks
    $price_input = str_replace('.', '', $price_input); // remove thousands separator
    $price_input = str_replace(',', '.', $price_input); // decimal to dot
    $iso_input = preg_replace('/\D+/', '', $iso_input); // cleaning

    // get municipality_id from users table
    $stmt = $conn->prepare(
        "SELECT municipality_id FROM users WHERE id = ?"
    );
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($municipality_id);
    $stmt->fetch();
    $stmt->close();

    // 2insert listing including municipality_id
    $stmt = $conn->prepare(
        "INSERT INTO listings (user_id, category_id, municipality_id, iso, price)
         VALUES (?, ?, ?, ?, ?)"
    );

    $stmt->bind_param(
        "iiisd",
        $user_id,
        $category_id,
        $municipality_id,
        $iso_input,
        $price_input
    );

    $stmt->execute();
    $stmt->close();

    // redirect
    header("Location: ../pages/my-profile.php");
    exit;
}