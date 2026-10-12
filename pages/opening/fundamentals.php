<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Opening Fundamentals | The Chess Room</title>
    <meta name="description"
        content="The core principles behind every opening. Select a book on the shelf to open it.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap">
    <link rel="stylesheet" href="../../assets/css/shelf.css">
</head>

<body>
    <div class="app">
        <nav class="side-nav" aria-label="Main"><a class="logo" href="../main_page.php"><i>&#9822;</i>
        <span><b>The Chess Room</b><small>A move forward, together</small></span></a>
            <h4>Community</h4>
            <a class="nav" href="../main_page.php">Announcements</a>
            <div class="group">Openings</div>
            <div class="sub">
                <a class="nav" href="../opening/fundamentals.php" aria-current="page">Fundamentals</a>
                <a class="nav" href="../opening/white.php">White</a>
                <a class="nav" href="../opening/black.php">Black</a>
            </div>
            <a class="nav" href="../puzzles.php">Puzzles</a>
            <a class="nav" href="../middle-game.php">Middle game</a>
            <a class="nav" href="../endgame.php">Endgame</a>
            <div class="tip">
                <b>Your next move starts here.</b>A good conversation. A great book. A little better at chess.
            </div>
        </nav>
        <div class="main">
            <div class="crumbs">Community &rsaquo; <b>Opening &rsaquo; Fundamentals</b></div>
            <div class="content">
                <div class="heading">
                    <div>
                        <h1>Opening Fundamentals</h1>
                        <p>The core principles behind every opening. Select a book on the shelf to open it.</p>
                    </div>
                    <button class="btn add-book-btn" type="button" data-add-book>Add book</button>
                </div>
                <section class="shelf">
                    <h2>Principles</h2>
                    <div class="books"><button class="spine" type="button" aria-pressed="false"
                            style="background:#7a2e2e;width:54px;height:205px"
                            data-title="The Ideas Behind the Chess Openings" data-author="Reuben Fine"
                            data-level="Beginner" data-topic="Opening principles"
                            aria-label="The Ideas Behind the Chess Openings, by Reuben Fine"><span>The Ideas Behind the
                                Chess Openings</span></button><button class="spine" type="button" aria-pressed="false"
                            style="background:#2f5d46;width:58px;height:198px" data-title="Winning Chess Openings"
                            data-author="Yasser Seirawan" data-level="Beginner" data-topic="Opening strategy"
                            aria-label="Winning Chess Openings, by Yasser Seirawan"><span>Winning Chess
                                Openings</span></button></div>
                    <div class="board"></div>
                </section>
                <section class="shelf">
                    <h2>Reference</h2>
                    <div class="books"><button class="spine" type="button" aria-pressed="false"
                            style="background:#27415e;width:60px;height:226px"
                            data-title="Fundamental Chess Openings" data-author="Paul van der Sterren"
                            data-level="All levels" data-topic="Comprehensive reference"
                            aria-label="Fundamental Chess Openings, by Paul van der Sterren"><span>Fundamental Chess
                                Openings</span></button><button class="spine" type="button" aria-pressed="false"
                            style="background:#6b4a1f;width:53px;height:205px"
                            data-title="Understanding the Chess Openings" data-author="Sam Collins"
                            data-level="Club player" data-topic="Modern explanations"
                            aria-label="Understanding the Chess Openings, by Sam Collins"><span>Understanding the Chess
                                Openings</span></button></div>
                    <div class="board"></div>
                </section>
            </div>
        </div>
    </div>
    <script src="../assets/js/shelf.js"></script>
    <script src="../../assets/js/add-book.js"></script>
</body>

</html>
