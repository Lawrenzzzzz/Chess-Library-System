<?php
session_start();
require_once __DIR__ . '/dbcon.php';

function back_to_form(array $params = []): void
{
    $qs = $params ? ('?' . http_build_query($params)) : '';
    header('Location: ../pages/index.php' . $qs);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    back_to_form();
}

$action = $_POST['action'] ?? '';

if ($action !== 'signup') {
    back_to_form(['notice' => 'That option is not available yet.']);
}

$name     = trim($_POST['name'] ?? '');
$email    = trim($_POST['email'] ?? '');
$password = (string) ($_POST['password'] ?? '');

if ($name === '' || $email === '' || $password === '') {
    back_to_form(['signup' => 1, 'error' => 'Please fill in your name, email and password.']);
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    back_to_form(['signup' => 1, 'error' => 'Please enter a valid email address.']);
}

if (strlen($password) < 8) {
    back_to_form(['signup' => 1, 'error' => 'Your password must be at least 8 characters.']);
}

$stmt = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->close();
    back_to_form(['signup' => 1, 'error' => 'An account with that email already exists.']);
}
$stmt->close();

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare('INSERT INTO users (name, email, password) VALUES (?, ?, ?)');
$stmt->bind_param('sss', $name, $email, $hash);

if (!$stmt->execute()) {
    $stmt->close();
    back_to_form(['signup' => 1, 'error' => 'We could not create your account. Please try again.']);
}

$userId = $stmt->insert_id;
$stmt->close();

$_SESSION['user_id']   = $userId;
$_SESSION['user_name'] = $name;

header('Location: ../pages/main_page.php');
exit;
