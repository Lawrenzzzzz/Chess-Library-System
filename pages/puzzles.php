<?php
session_start();
require_once __DIR__ . '/../backend/books_repo.php';
$role = $_SESSION['role'] ?? 'guest';
$isAdmin = $role === 'admin';
$shelves = books_grouped($conn, 'puzzles');
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Puzzles | The Chess Room</title>
    <meta name="description" content="Puzzle books for training tactics. Select a book on the shelf to open it.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap">
    <link rel="stylesheet" href="../assets/css/shelf.css">
</head>

<body data-role="<?= htmlspecialchars($role) ?>" data-category="puzzles" data-books-api="../backend/books.php">
    <div class="app">
        <nav class="side-nav" aria-label="Main"><a class="logo" href="main_page.php"><i>&#9822;</i>
        <span><b>The Chess Room</b><small>A move forward, together</small></span></a>
            <h4>Community</h4>
            <a class="nav" href="main_page.php">Announcements</a>
            <div class="group">Openings</div>
            <div class="sub">
                <a class="nav" href="opening/fundamentals.php">Fundamentals</a>
                <a class="nav" href="opening/white.php">White</a>
                <a class="nav" href="opening/black.php">Black</a>
            </div>
            <a class="nav" href="puzzles.php" aria-current="page">Puzzles</a>
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
            <div class="crumbs">Community &rsaquo; <b>Puzzles</b></div>
            <div class="content">
                <div class="heading">
                    <div>
                        <h1>Puzzles</h1>
                        <p>Puzzle books for training tactics. Select a book on the shelf to open it.</p>
                    </div>
                    <?php if ($isAdmin): ?>
                    <button class="btn add-book-btn" type="button" data-add-book>Add book</button>
                    <?php endif; ?>
                </div>

                <?php render_shelves($shelves, '../uploads/books/'); ?>
            </div>
        </div>
    </div>
    <script src="../assets/js/pdf-viewer.js"></script>
    <script src="../assets/js/books.js"></script>
</body>

</html>