<?php
session_start();
require_once __DIR__ . '/dbcon.php';

define('UPLOAD_DIR', __DIR__ . '/../uploads/books/');
define('MAX_UPLOAD_BYTES', 25 * 1024 * 1024); // 25 MB

header('Content-Type: application/json; charset=utf-8');

function respond(int $code, array $data): void
{
    http_response_code($code);
    echo json_encode($data);
    exit;
}

function delete_pdf(?string $stored): void
{
    if ($stored === null || $stored === '') {
        return;
    }
    $path = UPLOAD_DIR . $stored;
    if (is_file($path)) {
        @unlink($path);
    }
}

/**
 * Returns ['stored' => ..., 'original' => ...] on success, or null when no
 * file was sent (and it was optional). On failure null is returned and $error
 * is populated.
 */
function upload_pdf(string $field, bool $required, string &$error): ?array
{
    $has = isset($_FILES[$field]) && $_FILES[$field]['error'] !== UPLOAD_ERR_NO_FILE;

    if (!$has) {
        if ($required) {
            $error = 'Please choose a PDF file to upload.';
        }
        return null;
    }

    $file = $_FILES[$field];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        $error = $file['error'] === UPLOAD_ERR_INI_SIZE || $file['error'] === UPLOAD_ERR_FORM_SIZE
            ? 'That PDF is too large (max 25 MB).'
            : 'The upload failed. Please try again.';
        return null;
    }

    if ($file['size'] > MAX_UPLOAD_BYTES) {
        $error = 'That PDF is too large (max 25 MB).';
        return null;
    }

    if (strtolower(pathinfo($file['name'], PATHINFO_EXTENSION)) !== 'pdf') {
        $error = 'Only PDF files are allowed.';
        return null;
    }

    $handle = @fopen($file['tmp_name'], 'rb');
    $signature = $handle ? fread($handle, 5) : '';
    if ($handle) {
        fclose($handle);
    }
    if ($signature !== '%PDF-') {
        $error = 'That file does not look like a valid PDF.';
        return null;
    }

    if (!is_dir(UPLOAD_DIR)) {
        @mkdir(UPLOAD_DIR, 0777, true);
    }

    $stored = bin2hex(random_bytes(16)) . '.pdf';
    if (!move_uploaded_file($file['tmp_name'], UPLOAD_DIR . $stored)) {
        $error = 'Could not save the uploaded file.';
        return null;
    }

    $original = substr(basename($file['name']), 0, 200);

    return ['stored' => $stored, 'original' => $original];
}

if (($_SESSION['role'] ?? '') !== 'admin') {
    respond(403, ['error' => 'Only an administrator can manage books.']);
}

$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
if (stripos($contentType, 'application/json') !== false) {
    $decoded = json_decode(file_get_contents('php://input'), true);
    $input = is_array($decoded) ? $decoded : [];
} else {
    $input = $_POST;
}

$action   = $input['action'] ?? '';
$title    = trim($input['title'] ?? '');
$author   = trim($input['author'] ?? '');
$section  = trim($input['section'] ?? '');
$level    = trim($input['level'] ?? '');
$topic    = trim($input['topic'] ?? '');
$category = trim($input['category'] ?? '');

$COLORS = ['#7a2e2e', '#2f5d46', '#27415e', '#6b4a1f', '#4a3220', '#1f5c46'];
$SIZES  = [
    [52, 184], [56, 212], [60, 170], [57, 226], [44, 184],
    [63, 177], [54, 205], [58, 198], [59, 219], [53, 205]
];

if ($action === 'create') {
    if ($title === '' || $category === '') {
        respond(422, ['error' => 'A title is required.']);
    }
    if ($section === '') {
        $section = 'General';
    }

    $error = '';
    $upload = upload_pdf('file', true, $error);
    if ($upload === null) {
        respond(422, ['error' => $error !== '' ? $error : 'Please choose a PDF file to upload.']);
    }

    $color = $COLORS[array_rand($COLORS)];
    [$width, $height] = $SIZES[array_rand($SIZES)];
    $uid = (int) ($_SESSION['user_id'] ?? 0);

    $stmt = $conn->prepare(
        'INSERT INTO books (category, section, title, author, level, topic, color, width, height, created_by, file, original_name)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->bind_param(
        'sssssssiiiss',
        $category, $section, $title, $author, $level, $topic,
        $color, $width, $height, $uid, $upload['stored'], $upload['original']
    );

    if (!$stmt->execute()) {
        $stmt->close();
        delete_pdf($upload['stored']);
        respond(500, ['error' => 'Could not save the book.']);
    }
    $id = $stmt->insert_id;
    $stmt->close();

    respond(200, ['ok' => true, 'id' => $id]);
}

if ($action === 'update') {
    $id = (int) ($input['id'] ?? 0);
    if ($id <= 0 || $title === '') {
        respond(422, ['error' => 'A title is required.']);
    }
    if ($section === '') {
        $section = 'General';
    }

    $stmt = $conn->prepare('SELECT file FROM books WHERE id = ? LIMIT 1');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $existing = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$existing) {
        respond(404, ['error' => 'That book no longer exists.']);
    }

    $error = '';
    $upload = upload_pdf('file', false, $error);
    if ($error !== '') {
        respond(422, ['error' => $error]);
    }

    if ($upload !== null) {
        $stmt = $conn->prepare(
            'UPDATE books SET section = ?, title = ?, author = ?, level = ?, topic = ?, file = ?, original_name = ? WHERE id = ?'
        );
        $stmt->bind_param(
            'sssssssi',
            $section, $title, $author, $level, $topic,
            $upload['stored'], $upload['original'], $id
        );
    } else {
        $stmt = $conn->prepare(
            'UPDATE books SET section = ?, title = ?, author = ?, level = ?, topic = ? WHERE id = ?'
        );
        $stmt->bind_param('sssssi', $section, $title, $author, $level, $topic, $id);
    }

    if (!$stmt->execute()) {
        $stmt->close();
        if ($upload !== null) {
            delete_pdf($upload['stored']);
        }
        respond(500, ['error' => 'Could not update the book.']);
    }
    $stmt->close();

    if ($upload !== null) {
        delete_pdf($existing['file']);
    }

    respond(200, ['ok' => true]);
}

if ($action === 'delete') {
    $id = (int) ($input['id'] ?? 0);
    if ($id <= 0) {
        respond(422, ['error' => 'Invalid book.']);
    }

    $stmt = $conn->prepare('SELECT file FROM books WHERE id = ? LIMIT 1');
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $existing = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$existing) {
        respond(404, ['error' => 'That book no longer exists.']);
    }

    $stmt = $conn->prepare('DELETE FROM books WHERE id = ?');
    $stmt->bind_param('i', $id);
    if (!$stmt->execute()) {
        $stmt->close();
        respond(500, ['error' => 'Could not delete the book.']);
    }
    $stmt->close();

    delete_pdf($existing['file']);

    respond(200, ['ok' => true]);
}

respond(400, ['error' => 'Unknown action.']);
