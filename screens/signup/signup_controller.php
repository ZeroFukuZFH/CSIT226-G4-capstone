<?php
require_once '../../app/authservice.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (isset($_SESSION['email'])) {
    header('Location: ../dashboard/dashboard_layout.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signup'])) {
    $firstname        = trim($_POST['firstname'] ?? '');
    $lastname         = trim($_POST['lastname'] ?? '');
    $email            = trim($_POST['email'] ?? '');
    $password         = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $username         = $firstname . ' ' . $lastname;

    if (empty($firstname) || empty($lastname) || empty($email) || empty($password)) {
        echo "All fields are required.";
        exit();
    }

    if ($password !== $confirm_password) {
        echo "Passwords do not match.";
        exit();
    }

    $auth = new AuthService();
    if ($auth->signup($username, $email, $password)) {
        header('Location: ../login/login_layout.html');
        exit();
    } else {
        echo "Signup failed. Email may already be in use.";
    }
}
?>
