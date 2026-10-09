<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';

$token = (string)($_GET['token'] ?? '');
$uid = $token !== '' ? find_token($token, 'verify', true) : null;

if ($uid) {
    db()->prepare('UPDATE users SET email_verified = 1 WHERE id = ?')->execute([$uid]);
    header('Location: ' . url('/?verified=1'));
} else {
    header('Location: ' . url('/?auth_error=verify'));
}
exit;
