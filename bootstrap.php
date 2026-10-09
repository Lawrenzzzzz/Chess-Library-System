<?php
declare(strict_types=1);

define('ROOT', dirname(__DIR__));
$GLOBALS['config'] = require ROOT . '/config.php';

function cfg(string $key, $default = null) {
    $v = $GLOBALS['config'];
    foreach (explode('.', $key) as $part) {
        if (!is_array($v) || !array_key_exists($part, $v)) return $default;
        $v = $v[$part];
    }
    return $v;
}

session_set_cookie_params([
    'lifetime' => 0, 'path' => '/', 'httponly' => true, 'samesite' => 'Lax',
    'secure' => !empty($_SERVER['HTTPS']),
]);
session_start();

/* ---------- URLs ---------- */
function base_path(): string {
    $doc = realpath($_SERVER['DOCUMENT_ROOT'] ?? '') ?: '';
    $root = realpath(ROOT) ?: ROOT;
    $p = str_replace('\\', '/', substr($root, strlen($doc)));
    return rtrim($p, '/');
}
function url(string $path = ''): string { return base_path() . '/' . ltrim($path, '/'); }
function abs_url(string $path = ''): string {
    $scheme = !empty($_SERVER['HTTPS']) ? 'https' : 'http';
    return $scheme . '://' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . url($path);
}
function redirect(string $path): never { header('Location: ' . url($path)); exit; }
function e(?string $s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

/* ---------- Database ---------- */
function db(): PDO {
    static $pdo = null;
    if ($pdo) return $pdo;
    $path = cfg('db_path');
    if (!is_dir(dirname($path))) mkdir(dirname($path), 0750, true);
    $pdo = new PDO('sqlite:' . $path, null, null, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    $pdo->exec('PRAGMA foreign_keys = ON');
    $pdo->exec('CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email TEXT NOT NULL UNIQUE,
        password_hash TEXT,
        display_name TEXT NOT NULL,
        google_id TEXT,
        email_verified INTEGER NOT NULL DEFAULT 0,
        created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
    )');
    $pdo->exec('CREATE TABLE IF NOT EXISTS tokens (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
        type TEXT NOT NULL,
        token_hash TEXT NOT NULL,
        expires_at INTEGER NOT NULL
    )');
    return $pdo;
}

/* ---------- Auth ---------- */
function current_user(): ?array {
    if (empty($_SESSION['user_id'])) return null;
    $st = db()->prepare('SELECT id, email, display_name FROM users WHERE id = ?');
    $st->execute([$_SESSION['user_id']]);
    return $st->fetch() ?: null;
}
function login_user(int $id): void {
    session_regenerate_id(true);
    $_SESSION['user_id'] = $id;
}
function logout_user(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
    }
    session_destroy();
}
function create_token(int $userId, string $type, int $ttl): string {
    $raw = bin2hex(random_bytes(32));
    db()->prepare('DELETE FROM tokens WHERE user_id = ? AND type = ?')->execute([$userId, $type]);
    db()->prepare('INSERT INTO tokens (user_id, type, token_hash, expires_at) VALUES (?,?,?,?)')
        ->execute([$userId, $type, hash('sha256', $raw), time() + $ttl]);
    return $raw;
}
/** Returns the user_id if the token is valid, else null. */
function find_token(string $raw, string $type, bool $consume = false): ?int {
    $st = db()->prepare('SELECT id, user_id FROM tokens WHERE token_hash = ? AND type = ? AND expires_at > ?');
    $st->execute([hash('sha256', $raw), $type, time()]);
    $row = $st->fetch();
    if (!$row) return null;
    if ($consume) db()->prepare('DELETE FROM tokens WHERE id = ?')->execute([$row['id']]);
    return (int)$row['user_id'];
}

/* ---------- CSRF ---------- */
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf'];
}
function csrf_verify(): void {
    $sent = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (!hash_equals(csrf_token(), $sent)) json_out(['error' => 'Your session expired. Please refresh the page.'], 419);
}

/* ---------- Misc ---------- */
function json_out(array $data, int $code = 200): never {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}
function send_mail(string $to, string $subject, string $body): void {
    if (cfg('mail_to_log')) {
        file_put_contents(ROOT . '/data/mail.log',
            "[" . date('c') . "] To: $to\nSubject: $subject\n$body\n\n", FILE_APPEND | LOCK_EX);
        return;
    }
    $headers = "From: " . cfg('mail_from') . "\r\nContent-Type: text/plain; charset=utf-8";
    @mail($to, $subject, $body, $headers);
}

/* ---------- Lucide-style inline icons ---------- */
function icon(string $name, int $size = 0, string $class = ''): string {
    static $paths = [
        'arrow-up-right' => '<path d="M7 7h10v10"/><path d="M7 17 17 7"/>',
        'arrow-right'    => '<path d="M5 12h14"/><path d="m12 5 7 7-7 7"/>',
        'book-open'      => '<path d="M12 7v14"/><path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"/>',
        'graduation-cap' => '<path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/><path d="M22 10v6"/><path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"/>',
        'library'        => '<path d="m16 6 4 14"/><path d="M12 6v14"/><path d="M8 8v12"/><path d="M4 4v16"/>',
        'shield-check'   => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/>',
        'mail'           => '<path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"/><rect x="2" y="4" width="20" height="16" rx="2"/>',
        'user-round'     => '<circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/>',
        'lock-keyhole'   => '<circle cx="12" cy="16" r="1"/><rect x="3" y="10" width="18" height="12" rx="2"/><path d="M7 10V7a5 5 0 0 1 10 0v3"/>',
        'eye'            => '<path d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0"/><circle cx="12" cy="12" r="3"/>',
        'eye-off'        => '<path d="M10.733 5.076a10.744 10.744 0 0 1 11.205 6.575 1 1 0 0 1 0 .696 10.747 10.747 0 0 1-1.444 2.49"/><path d="M14.084 14.158a3 3 0 0 1-4.242-4.242"/><path d="M17.479 17.499a10.75 10.75 0 0 1-15.417-5.151 1 1 0 0 1 0-.696 10.75 10.75 0 0 1 4.446-5.143"/><path d="m2 2 20 20"/>',
        'check-circle'   => '<circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/>',
        'log-out'        => '<path d="m16 17 5-5-5-5"/><path d="M21 12H9"/><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>',
        'loader'         => '<path d="M21 12a9 9 0 1 1-6.219-8.56"/>',
    ];
    $dim = $size ? " width=\"$size\" height=\"$size\"" : ' width="24" height="24"';
    $cls = $class ? " class=\"$class\"" : '';
    return '<svg xmlns="http://www.w3.org/2000/svg"' . $dim . $cls . ' viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . ($paths[$name] ?? '') . '</svg>';
}

/* ---------- Layout ---------- */
function page_head(string $title, string $desc, string $bodyClass = ''): void { ?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($title) ?></title>
<meta name="description" content="<?= e($desc) ?>">
<meta property="og:title" content="<?= e($title) ?>">
<meta property="og:description" content="<?= e($desc) ?>">
<meta property="og:type" content="website">
<meta name="twitter:card" content="summary_large_image">
<meta name="csrf-token" content="<?= e(csrf_token()) ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;450;500;550;600;650;700&family=Libre+Caslon+Display&display=swap">
<link rel="stylesheet" href="<?= e(url('assets/css/style.css')) ?>">
<link rel="icon" href="<?= e(url('favicon.ico')) ?>" type="image/x-icon">
</head>
<body class="<?= e($bodyClass) ?>" data-api="<?= e(url('api/auth.php')) ?>" data-home="<?= e(url('/')) ?>">
<?php }

function page_foot(): void { ?>
<script src="<?= e(url('assets/js/app.js')) ?>" defer></script>
</body>
</html>
<?php }
