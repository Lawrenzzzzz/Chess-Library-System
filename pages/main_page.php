<?php session_start(); ?>
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

<body>
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
                    <span class="pill">Our community is growing</span>
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
                        <h2>Community announcements<span class="n">3</span></h2>
                        <article class="post">
                            <div class="who"><span class="av">MR</span><span>Mark Rosario<span
                                        class="badge">Admin</span><small>Today at 9:30 AM</small></span><span
                                    class="pinned">Pinned announcement</span></div>
                            <h3>A new home for our chess community</h3>
                            <p>Welcome to The Chess Room! A place for curious minds, better moves, and a shared love of
                                the game. Explore the opening rooms, sharpen your tactics, and discover your next great
                                chess read.</p>
                            <p>This is just the beginning. We're glad you're here.</p><span class="tag">Community
                                update</span>
                        </article>
                        <article class="post">
                            <div class="who"><span class="av">MR</span><span>Mark Rosario<span
                                        class="badge">Admin</span><small>Yesterday</small></span></div>
                            <h3>New shelf: endgame books</h3>
                            <p>The endgame room now lists books on technique and theory.</p><span
                                class="tag">Library</span>
                        </article>
                        <article class="post">
                            <div class="who"><span class="av">MR</span><span>Mark Rosario<span
                                        class="badge">Admin</span><small>Last week</small></span></div>
                            <h3>Share a book you love</h3>
                            <p>Tell us which chess book shaped your game and credit the author.</p><span
                                class="tag">Community update</span>
                        </article>
                    </section>
                    <aside class="shelf-side" id="bookshelf">
                        <h2>From the bookshelf <a href="endgame.html">View all</a></h2>
                        <div class="row">
                            <div class="mini" style="background:#16181a"></div>
                            <div>
                                <h3>My System</h3>
                                <p>Aron Nimzowitsch</p><small>Shared by Daniel K.</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mini" style="background:#8a5a1f"></div>
                            <div>
                                <h3>Silman's Complete Endgame Course</h3>
                                <p>Jeremy Silman</p><small>Shared by Alex R.</small>
                            </div>
                        </div>
                        <div class="row">
                            <div class="mini" style="background:#2b5a8a"></div>
                            <div>
                                <h3>Fundamental Chess Openings</h3>
                                <p>Paul van der Sterren</p><small>Shared by Sarah M.</small>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </div>
</body>

</html>