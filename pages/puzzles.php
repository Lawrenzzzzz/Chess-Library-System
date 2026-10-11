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

<body>
    <div class="app">
        <nav class="side-nav" aria-label="Main"><a class="logo" href="main_page.php"><i>&#9822;</i>
        <span><b>The Chess Room</b><small>A move forward, together</small></span></a>
            <h4>Community</h4>
            <a class="nav" href="main_page.php">Announcements</a>
            <div class="group">Openings</div>
            <div class="sub">
                <a class="nav" href="opening/white.php">White</a>
                <a class="nav" href="openings/black.php">Black</a>
            </div>
            <a class="nav" href="puzzles.php" aria-current="page">Puzzles</a>
            <a class="nav" href="middle-game.php">Middle game</a>
            <a class="nav" href="endgame.php">Endgame</a>
            <div class="tip">
                <b>Your next move starts here.</b>A good conversation. A great book. A little better at chess.
            </div>
        </nav>
        <div class="main">
            <div class="crumbs">Community &rsaquo; <b>Puzzles</b></div>
            <div class="content">
                <div class="heading">
                    <div>
                        <h1>Puzzles</h1>
                        <p>Puzzle books for training tactics. Select a book on the shelf to open it.</p>
                    </div>
                </div>
                <section class="shelf">
                    <h2>Tactics</h2>
                    <div class="books"><button class="spine" type="button" aria-pressed="false"
                            style="background:#7a2e2e;width:59px;height:219px" data-title="Chess Tactics for Champions"
                            data-author="Susan Polgar" data-level="Intermediate" data-topic="Tactical themes"
                            aria-label="Chess Tactics for Champions, by Susan Polgar"><span>Chess Tactics for
                                Champions</span></button><button class="spine" type="button" aria-pressed="false"
                            style="background:#2f5d46;width:58px;height:198px"
                            data-title="1001 Chess Exercises for Beginners" data-author="Franco Masetti"
                            data-level="Beginner" data-topic="Mixed tactics"
                            aria-label="1001 Chess Exercises for Beginners, by Franco Masetti"><span>1001 Chess
                                Exercises for Beginners</span></button><button class="spine" type="button"
                            aria-pressed="false" style="background:#27415e;width:63px;height:177px"
                            data-title="The Woodpecker Method" data-author="Axel Smith &amp; Hans Tikkanen"
                            data-level="Intermediate" data-topic="Repetition training"
                            aria-label="The Woodpecker Method, by Axel Smith &amp; Hans Tikkanen"><span>The Woodpecker
                                Method</span></button></div>
                    <div class="board"></div>
                </section>
            </div>
        </div>
    </div>
    <script src="assets/js/shelf.js"></script>
</body>

</html>