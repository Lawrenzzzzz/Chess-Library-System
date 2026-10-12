<?php
session_start();
require_once __DIR__ . '/dbcon.php';

function fail(string $message): void
{
    header('Location: ../pages/reset-password.php?error=' . urlencode($message));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../pages/index.php');
    exit;
}

$email    = trim($_POST['email'] ?? '');
$password = (string) ($_POST['password'] ?? '');
$confirm  = (string) ($_POST['confirm'] ?? '');

if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    fail('Please enter a valid email address.');
}

if ($password === '' || $confirm === '') {
    fail('Please fill in both password fields.');
}

if (strlen($password) < 8) {
    fail('Your password must be at least 8 characters.');
}

if ($password !== $confirm) {
    fail('The two passwords do not match.');
}

$stmt = $conn->prepare('SELECT id FROM users WHERE email = ? LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$user) {
    fail('We could not find an account for that email address.');
}

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare('UPDATE users SET password = ? WHERE id = ?');
$stmt->bind_param('si', $hash, $user['id']);
$stmt->execute();
$stmt->close();

header('Location: ../pages/reset-password.php?done=1');
exit;
