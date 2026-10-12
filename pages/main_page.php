<?php
session_start();
require_once __DIR__ . '/../backend/announcements_repo.php';
require_once __DIR__ . '/../backend/books_repo.php';
$role = $_SESSION['role'] ?? 'guest';
$isAdmin = $role === 'admin';
$announcements = announcements_all($conn);
$shelfBooks = all_books($conn);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Announcements | The Chess Room</title>
    <meta name="description" content="News, conversations, and shared chess books from The Chess Room community.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap">
    <link rel="stylesheet" href="../assets/css/shelf.css">
</head>

<body data-role="<?= htmlspecialchars($role) ?>" data-announcements-api="../backend/announcements.php">
    <div class="app">
        <nav class="side-nav" aria-label="Main">
            <a class="logo" href="main_page.php"><i>&#9822;</i>
            <span><b>The Chess Room</b><small>A move forward, together</small></span></a>

            <h4>Community</h4>
            <a class="nav" href="main_page.php" aria-current="page">Announcements</a>
            <div class="group">Openings</div>
            <div class="sub">
                <a class="nav" href="opening/fundamentals.php">Fundamentals</a>
                <a class="nav" href="opening/white.php">White</a>
                <a class="nav" href="opening/black.php">Black</a>
            </div>
            <a class="nav" href="puzzles.php">Puzzles</a>
            <a class="nav" href="middle-game.php">Middle game</a>
            <a class="nav" href="endgame.php">Endgame</a>
            
            <?php if (isset($_SESSION['user_id'])): ?>
            <div class="side-account">
                <small>Signed in as <b><?= htmlspecialchars($_SESSION['user_name'] ?: 'Member') ?></b></small>
                <a class="logout-btn" href="../backend/logout.php">Log out</a>
            </div>
            <?php endif; ?>
        </nav>
        <div class="main">
            <div class="crumbs">Community &rsaquo; <b>Announcements</b></div>
            <div class="content">
                <div class="heading">
                    <div>
                        <h1>Announcements</h1>
                        <p>The latest news, updates, and a little inspiration.</p>
                    </div>
                    <?php if ($isAdmin): ?>
                    <button class="btn add-book-btn" type="button" data-add-announcement>New announcement</button>
                    <?php else: ?>
                    <span class="pill">Our community is growing</span>
                    <?php endif; ?>
                </div>
                <section class="hero" aria-labelledby="hh">
                    <div class="hero-copy">
                        <div class="eyebrow">WELCOME TO THE CHESS ROOM</div>
                        <h2 id="hh">Great minds.<br>Better moves.</h2>
                        <p>A space to talk chess, share your favorite books, and grow together, one move at a time.</p>
                        <a class="btn" href="#bookshelf">Explore the library</a>
                    </div>
                </section>
                <div class="cols">
                    <section>
                        <h2>Community announcements<span class="n"><?= count($announcements) ?></span></h2>
                        <?php render_announcements($announcements, $isAdmin); ?>
                    </section>
                    <aside class="shelf-side" id="bookshelf">
                        <h2>From the bookshelf
                            <button type="button" class="view-all" data-shelf-toggle hidden>View all</button>
                        </h2>
                        <?php if ($shelfBooks): ?>
                        <div class="shelf-list" data-shelf-list>
                            <?php foreach ($shelfBooks as $i => $b):
                                $hasFile = !empty($b['file']);
                                $fileUrl = $hasFile ? '../uploads/books/' . rawurlencode($b['file']) : '';
                                $title = htmlspecialchars($b['title'], ENT_QUOTES);
                            ?>
                            <button class="row<?= $i >= 4 ? ' shelf-extra' : '' ?><?= $hasFile ? '' : ' is-empty' ?>"
                                type="button"<?= $hasFile ? ' data-file="' . htmlspecialchars($fileUrl, ENT_QUOTES) . '" data-title="' . $title . '"' : ' disabled' ?>>
                                <span class="mini" style="background:<?= htmlspecialchars($b['color'], ENT_QUOTES) ?>"></span>
                                <span class="shelf-info">
                                    <span class="shelf-title"><?= htmlspecialchars($b['title']) ?></span>
                                    <span class="shelf-author"><?= htmlspecialchars($b['author']) ?></span>
                                    <span class="shelf-meta"><?= htmlspecialchars(category_label($b['category'])) ?> &middot; <?= $hasFile ? 'Read PDF' : 'No PDF yet' ?></span>
                                </span>
                            </button>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                        <p class="shelf-empty">No books yet.</p>
                        <?php endif; ?>
                    </aside>
                </div>
            </div>
        </div>
    </div>
    <script src="../assets/js/pdf-viewer.js"></script>
    <script src="../assets/js/bookshelf.js"></script>
    <script src="../assets/js/announcements.js"></script>
</body>

</html>