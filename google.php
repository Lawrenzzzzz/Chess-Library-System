<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';

$clientId = (string)cfg('google.client_id', '');
$secret   = (string)cfg('google.client_secret', '');
$fail = fn() => redirect('/?auth_error=google');

if ($clientId === '' || $secret === '') $fail();

$redirectUri = abs_url('google.php');

/* Step 1: send the visitor to Google */
if (!isset($_GET['code'])) {
    if (isset($_GET['error'])) $fail();
    $_SESSION['oauth_state'] = bin2hex(random_bytes(16));
    header('Location: https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query([
        'client_id'     => $clientId,
        'redirect_uri'  => $redirectUri,
        'response_type' => 'code',
        'scope'         => 'openid email profile',
        'state'         => $_SESSION['oauth_state'],
        'prompt'        => 'select_account',
    ]));
    exit;
}

/* Step 2: handle the callback */
$state = (string)($_GET['state'] ?? '');
if (empty($_SESSION['oauth_state']) || !hash_equals($_SESSION['oauth_state'], $state)) $fail();
unset($_SESSION['oauth_state']);

function http_json(string $url, array $opts): ?array {
    $ch = curl_init($url);
    curl_setopt_array($ch, $opts + [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 15]);
    $res = curl_exec($ch);
    curl_close($ch);
    $data = is_string($res) ? json_decode($res, true) : null;
    return is_array($data) ? $data : null;
}

$tok = http_json('https://oauth2.googleapis.com/token', [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => http_build_query([
        'code' => $_GET['code'], 'client_id' => $clientId, 'client_secret' => $secret,
        'redirect_uri' => $redirectUri, 'grant_type' => 'authorization_code',
    ]),
]);
if (empty($tok['access_token'])) $fail();

$info = http_json('https://openidconnect.googleapis.com/v1/userinfo', [
    CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $tok['access_token']],
]);
if (empty($info['email']) || empty($info['email_verified'])) $fail();

$email = strtolower($info['email']);
$st = db()->prepare('SELECT id FROM users WHERE email = ?');
$st->execute([$email]);
$row = $st->fetch();

if ($row) {
    $id = (int)$row['id'];
    db()->prepare('UPDATE users SET google_id = ?, email_verified = 1 WHERE id = ?')->execute([$info['sub'] ?? null, $id]);
} else {
    db()->prepare('INSERT INTO users (email, display_name, google_id, email_verified) VALUES (?,?,?,1)')
        ->execute([$email, mb_substr((string)($info['name'] ?? 'chess player'), 0, 100), $info['sub'] ?? null]);
    $id = (int)db()->lastInsertId();
}

login_user($id);
redirect('/');
