<?php
require_once __DIR__ . '/dbcon.php';

function books_grouped(mysqli $conn, string $category): array
{
    $groups = [];
    $stmt = $conn->prepare(
        'SELECT id, section, title, author, level, topic, color, width, height, file, original_name
         FROM books
         WHERE category = ?
         ORDER BY id ASC'
    );
    $stmt->bind_param('s', $category);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $section = $row['section'] !== '' ? $row['section'] : 'General';
        $groups[$section][] = $row;
    }

    $stmt->close();
    return $groups;
}

function render_shelves(array $groups, string $fileBase = ''): void
{
    if (!$groups) {
        echo '<p class="empty-shelf">No books on this shelf yet.</p>';
        return;
    }

    foreach ($groups as $section => $books) {
        $sectionAttr = htmlspecialchars($section, ENT_QUOTES);
        echo '<section class="shelf">';
        echo '<h2>' . htmlspecialchars($section) . '</h2>';
        echo '<div class="books">';

        foreach ($books as $b) {
            $title  = htmlspecialchars($b['title'], ENT_QUOTES);
            $author = htmlspecialchars($b['author'], ENT_QUOTES);
            $level  = htmlspecialchars($b['level'], ENT_QUOTES);
            $topic  = htmlspecialchars($b['topic'], ENT_QUOTES);
            $color  = htmlspecialchars($b['color'], ENT_QUOTES);
            $id     = (int) $b['id'];
            $width  = (int) $b['width'];
            $height = (int) $b['height'];

            $file      = isset($b['file']) ? (string) $b['file'] : '';
            $fileName  = isset($b['original_name']) ? (string) $b['original_name'] : '';
            $fileAttr  = $file !== '' ? htmlspecialchars($fileBase . rawurlencode($file), ENT_QUOTES) : '';
            $nameAttr  = htmlspecialchars($fileName, ENT_QUOTES);

            echo '<button class="spine" type="button" aria-pressed="false"'
                . ' style="background:' . $color . ';width:' . $width . 'px;height:' . $height . 'px"'
                . ' data-id="' . $id . '"'
                . ' data-title="' . $title . '"'
                . ' data-author="' . $author . '"'
                . ' data-level="' . $level . '"'
                . ' data-topic="' . $topic . '"'
                . ' data-section="' . $sectionAttr . '"'
                . ' data-file="' . $fileAttr . '"'
                . ' data-file-name="' . $nameAttr . '"'
                . ' aria-label="' . $title . ', by ' . $author . '">'
                . '<span>' . htmlspecialchars($b['title']) . '</span>'
                . '</button>';
        }

        echo '</div>';
        echo '<div class="board"></div>';
        echo '</section>';
    }
}
