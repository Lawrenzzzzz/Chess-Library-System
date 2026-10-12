<?php
session_start();
require_once __DIR__ . '/dbcon.php';

header('Content-Type: application/json; charset=utf-8');

function respond(int $code, array $data): void
{
    http_response_code($code);
    echo json_encode($data);
    exit;
}

if (($_SESSION['role'] ?? '') !== 'admin') {
    respond(403, ['error' => 'Only an administrator can manage announcements.']);
}

$contentType = $_SERVER['CONTENT_TYPE'] ?? '';
if (stripos($contentType, 'application/json') !== false) {
    $decoded = json_decode(file_get_contents('php://input'), true);
    $input = is_array($decoded) ? $decoded : [];
} else {
    $input = $_POST;
}

$action = $input['action'] ?? '';
$title  = trim($input['title'] ?? '');
$body   = trim($input['body'] ?? '');
$tag    = trim($input['tag'] ?? '');
$pinned = (isset($input['pinned']) && $input['pinned'] !== '0' && $input['pinned'] !== '') ? 1 : 0;

if ($action === 'create') {
    if ($title === '' || $body === '') {
        respond(422, ['error' => 'A title and a message are required.']);
    }

    $author = (string) ($_SESSION['user_name'] ?? '');
    if ($author === '') {
        $author = 'Administrator';
    }
    $uid = (int) ($_SESSION['user_id'] ?? 0);

    $stmt = $conn->prepare(
        'INSERT INTO announcements (title, body, tag, pinned, author_name, created_by) VALUES (?, ?, ?, ?, ?, ?)'
    );
    $stmt->bind_param('sssisi', $title, $body, $tag, $pinned, $author, $uid);

    if (!$stmt->execute()) {
        $stmt->close();
        respond(500, ['error' => 'Could not post the announcement.']);
    }
    $id = $stmt->insert_id;
    $stmt->close();

    respond(200, ['ok' => true, 'id' => $id]);
}

if ($action === 'update') {
    $id = (int) ($input['id'] ?? 0);
    if ($id <= 0 || $title === '' || $body === '') {
        respond(422, ['error' => 'A title and a message are required.']);
    }

    $stmt = $conn->prepare('UPDATE announcements SET title = ?, body = ?, tag = ?, pinned = ? WHERE id = ?');
    $stmt->bind_param('sssii', $title, $body, $tag, $pinned, $id);

    if (!$stmt->execute()) {
        $stmt->close();
        respond(500, ['error' => 'Could not update the announcement.']);
    }
    if ($stmt->affected_rows === 0) {
        $exists = $conn->query('SELECT 1 FROM announcements WHERE id = ' . $id)->num_rows;
        if (!$exists) {
            $stmt->close();
            respond(404, ['error' => 'That announcement no longer exists.']);
        }
    }
    $stmt->close();

    respond(200, ['ok' => true]);
}

if ($action === 'delete') {
    $id = (int) ($input['id'] ?? 0);
    if ($id <= 0) {
        respond(422, ['error' => 'Invalid announcement.']);
    }

    $stmt = $conn->prepare('DELETE FROM announcements WHERE id = ?');
    $stmt->bind_param('i', $id);

    if (!$stmt->execute()) {
        $stmt->close();
        respond(500, ['error' => 'Could not delete the announcement.']);
    }
    if ($stmt->affected_rows === 0) {
        $stmt->close();
        respond(404, ['error' => 'That announcement no longer exists.']);
    }
    $stmt->close();

    respond(200, ['ok' => true]);
}

respond(400, ['error' => 'Unknown action.']);
