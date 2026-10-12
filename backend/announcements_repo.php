<?php
require_once __DIR__ . '/dbcon.php';

function announcements_all(mysqli $conn): array
{
    $rows = [];
    $res = $conn->query(
        'SELECT id, title, body, tag, pinned, author_name, UNIX_TIMESTAMP(created_at) AS ts
         FROM announcements
         ORDER BY pinned DESC, created_at DESC'
    );

    if ($res) {
        while ($row = $res->fetch_assoc()) {
            $rows[] = $row;
        }
    }

    return $rows;
}

function relative_time(int $ts): string
{
    $diff = time() - $ts;

    if ($diff < 60) {
        return 'Just now';
    }
    if ($diff < 3600) {
        $m = (int) floor($diff / 60);
        return $m === 1 ? '1 minute ago' : $m . ' minutes ago';
    }
    if ($diff < 86400) {
        $h = (int) floor($diff / 3600);
        return $h === 1 ? '1 hour ago' : $h . ' hours ago';
    }
    if ($diff < 172800) {
        return 'Yesterday';
    }
    if ($diff < 604800) {
        return (int) floor($diff / 86400) . ' days ago';
    }
    return date('M j, Y', (int) $ts);
}

function announce_initials(string $name): string
{
    $parts = preg_split('/\s+/', trim($name)) ?: [];
    $initials = '';
    foreach ($parts as $part) {
        if ($part !== '') {
            $initials .= strtoupper(substr($part, 0, 1));
        }
        if (strlen($initials) >= 2) {
            break;
        }
    }
    return $initials !== '' ? $initials : 'A';
}

function render_announcements(array $items, bool $isAdmin): void
{
    if (!$items) {
        echo '<p class="announce-empty">No announcements yet.</p>';
        return;
    }

    foreach ($items as $a) {
        $id     = (int) $a['id'];
        $author = $a['author_name'] !== '' ? $a['author_name'] : 'Administrator';
        $tag    = trim($a['tag']);
        $pinned = !empty($a['pinned']);

        echo '<article class="post' . ($pinned ? ' is-pinned' : '') . '">';

        echo '<div class="who"><span class="av">' . htmlspecialchars(announce_initials($author)) . '</span>'
            . '<span>' . htmlspecialchars($author) . '<span class="badge">Admin</span>'
            . '<small>' . htmlspecialchars(relative_time((int) $a['ts'])) . '</small></span>'
            . ($pinned ? '<span class="pinned">Pinned announcement</span>' : '')
            . '</div>';

        echo '<h3>' . htmlspecialchars($a['title']) . '</h3>';
        echo '<p>' . nl2br(htmlspecialchars($a['body'])) . '</p>';

        if ($tag !== '') {
            echo '<span class="tag">' . htmlspecialchars($tag) . '</span>';
        }

        if ($isAdmin) {
            echo '<div class="announce-tools">'
                . '<button type="button" class="announce-tool" data-announce-edit'
                . ' data-id="' . $id . '"'
                . ' data-title="' . htmlspecialchars($a['title'], ENT_QUOTES) . '"'
                . ' data-tag="' . htmlspecialchars($a['tag'], ENT_QUOTES) . '"'
                . ' data-body="' . htmlspecialchars($a['body'], ENT_QUOTES) . '"'
                . ' data-pinned="' . ($pinned ? '1' : '0') . '">Edit</button>'
                . '<button type="button" class="announce-tools-del" data-announce-delete data-id="' . $id . '">Delete</button>'
                . '</div>';
        }

        echo '</article>';
    }
}
