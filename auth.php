<?php
declare(strict_types=1);
require __DIR__ . '/../includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') json_out(['error' => 'Method not allowed.'], 405);
csrf_verify();

$in = json_decode(file_get_contents('php://input') ?: '', true);
if (!is_array($in)) $in = [];
$action = (string)($in['action'] ?? '');

/* ---------- validation (mirrors the zod schemas) ---------- */
function v_email(mixed $v): string {
    $v = trim((string)$v);
    if (!filter_var($v, FILTER_VALIDATE_EMAIL) || mb_strlen($v) > 255) {
        json_out(['error' => 'Please enter a valid email address.'], 422);
    }
    return strtolower($v);
}
function v_password(mixed $v, string $msg = 'Use at least 8 characters for your password.'): string {
    $v = (string)$v;
    if (mb_strlen($v) < 8)   json_out(['error' => $msg], 422);
    if (mb_strlen($v) > 128) json_out(['error' => 'Password is too long.'], 422);
    return $v;
}

try {
    switch ($action) {

        case 'login': {
            $email = v_email($in['email'] ?? '');
            $st = db()->prepare('SELECT * FROM users WHERE email = ?');
            $st->execute([$email]);
            $u = $st->fetch();
            if (!$u || !$u['password_hash'] || !password_verify((string)($in['password'] ?? ''), $u['password_hash'])) {
                usleep(300000);
                json_out(['error' => 'Invalid email or password.'], 401);
            }
            if (cfg('require_email_confirmation') && !$u['email_verified']) {
                json_out(['error' => 'Please confirm your email address before signing in.'], 403);
            }
            login_user((int)$u['id']);
            json_out(['ok' => true]);
        }

        case 'signup': {
            $email = v_email($in['email'] ?? '');
            $password = v_password($in['password'] ?? '');
            $name = trim((string)($in['name'] ?? ''));
            if ($name === '')            json_out(['error' => 'Please enter your name.'], 422);
            if (mb_strlen($name) > 100)  json_out(['error' => 'Name is too long.'], 422);

            $st = db()->prepare('SELECT 1 FROM users WHERE email = ?');
            $st->execute([$email]);
            if ($st->fetch()) json_out(['error' => 'An account with this email already exists.'], 409);

            $confirm = (bool)cfg('require_email_confirmation');
            db()->prepare('INSERT INTO users (email, password_hash, display_name, email_verified) VALUES (?,?,?,?)')
                ->execute([$email, password_hash($password, PASSWORD_DEFAULT), $name, $confirm ? 0 : 1]);
            $id = (int)db()->lastInsertId();

            if ($confirm) {
                $token = create_token($id, 'verify', 86400);
                send_mail($email, 'Confirm your Chess Library account',
                    "Welcome, $name!\n\nConfirm your account:\n" . abs_url('verify.php?token=' . $token));
                json_out(['ok' => true, 'notice' => 'Check your email to confirm your account, then come back to sign in.']);
            }
            login_user($id);
            json_out(['ok' => true, 'loggedIn' => true]);
        }

        case 'forgot': {
            $email = v_email($in['email'] ?? '');
            $st = db()->prepare('SELECT id FROM users WHERE email = ?');
            $st->execute([$email]);
            if ($u = $st->fetch()) {
                $token = create_token((int)$u['id'], 'reset', 3600);
                send_mail($email, 'Reset your Chess Library password',
                    "Use this link to choose a new password (valid for 1 hour):\n" . abs_url('reset-password.php?token=' . $token));
            }
            // Same response whether or not the account exists.
            json_out(['ok' => true, 'notice' => 'If an account exists for this email, a password reset link is on its way.']);
        }

        case 'reset': {
            $token = (string)($in['token'] ?? '');
            $password = v_password($in['password'] ?? '', 'Use at least 8 characters.');
            if (!hash_equals($password, (string)($in['confirm'] ?? ''))) {
                json_out(['error' => 'Your passwords do not match.'], 422);
            }
            $uid = find_token($token, 'reset', true);
            if (!$uid) json_out(['error' => 'Please request a new password reset link.'], 410);
            db()->prepare('UPDATE users SET password_hash = ?, email_verified = 1 WHERE id = ?')
                ->execute([password_hash($password, PASSWORD_DEFAULT), $uid]);
            db()->prepare('DELETE FROM tokens WHERE user_id = ?')->execute([$uid]);
            json_out(['ok' => true]);
        }

        case 'logout': {
            logout_user();
            json_out(['ok' => true]);
        }

        default:
            json_out(['error' => 'Unknown action.'], 400);
    }
} catch (Throwable $ex) {
    error_log($ex->getMessage());
    json_out(['error' => 'Something went wrong. Please try again.'], 500);
}
