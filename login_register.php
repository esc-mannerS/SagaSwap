<?php

session_start();
require_once 'config.php';

//  redirection with error message
function redirect_with_error($error, $form = 'login') {
    $_SESSION[$form . '_error'] = $error;
    $_SESSION['active_form'] = $form;
    header("Location: login.php");
    exit();
}

// registration start
if (isset($_POST['register'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // checks if fields are empty
    if (empty($username) || empty($email) || empty($_POST['password'])) {
        redirect_with_error('Alle felter skal udfyldes!', 'register');
    }

    // validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        redirect_with_error('Ugyldig e-mail adresse!', 'register');
    }

    // Validate password strength
    if (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[!@#$%^&*])[A-Za-z\d!@#$%^&*]{12,}$/', $password)) {
        redirect_with_error('Adgangskode skal indeholde: mindst 12 tegn, 1 stort bogstav, 1 lille bogstav, 1 tal og 1 specialtegn.', 'register');
    }

    // checks if email already exists
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['register_error'] = 'E-mail bruges allerede!';
        $_SESSION['active_form'] = 'register';
    } else {
        // insert to db
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insert = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $insert->bind_param("sss", $username, $email, $hashed_password);
        $insert->execute();
    }

    header("Location: login.php");
    exit();
}
// registration end

// login start
if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // SAFE SELECT
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $username = $result->fetch_assoc();
        if (password_verify($password, $username['password'])) {
            $_SESSION['username'] = $username['username'];
            $_SESSION['email'] = $username['email'];
            header("Location: index.html");
            exit();
        }
    }

    $_SESSION['login_error'] = 'Forkert email eller adgangskode!';
    $_SESSION['active_form'] = 'login';
    header("Location: login.php");
    exit();
}
// login end

?>
