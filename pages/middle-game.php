<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Middle game | The Chess Room</title>
    <meta name="description" content="Books on plans, structures and attack. Select a book on the shelf to open it.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;700&display=swap">
    <link rel="stylesheet" href="assets/css/shelf.css">
</head>

<body>
    <div class="app">
        <nav class="side-nav" aria-label="Main"><a class="logo" href="index.html"><i>&#9822;</i><span><b>The Chess
                        Room</b><small>A move forward, together</small></span></a>
            <h4>Community</h4><a class="nav" href="index.html">Announcements</a>
            <div class="group">Opening</div>
            <div class="sub"><a class="nav" href="openings/white.html">White</a><a class="nav"
                    href="openings/black.html">Black</a></div><a class="nav" href="puzzles.html">Puzzles</a><a
                class="nav" href="middle-game.html" aria-current="page">Middle game</a><a class="nav"
                href="endgame.html">Endgame</a>
            <div class="tip"><b>Your next move starts here.</b>A good conversation. A great book. A little better at
                chess.</div>
        </nav>
        <div class="main">
            <div class="crumbs">Community &rsaquo; <b>Middle game</b></div>
            <div class="content">
                <div class="heading">
                    <div>
                        <h1>Middle game</h1>
                        <p>Books on plans, structures and attack. Select a book on the shelf to open it.</p>
                    </div>
                </div>
                <section class="shelf">
                    <h2>Strategy</h2>
                    <div class="books"><button class="spine" type="button" aria-pressed="false"
                            style="background:#7a2e2e;width:50px;height:198px" data-title="Think Like a Grandmaster"
                            data-author="Alexander Kotov" data-level="Intermediate" data-topic="Calculation"
                            aria-label="Think Like a Grandmaster, by Alexander Kotov"><span>Think Like a
                                Grandmaster</span></button><button class="spine" type="button" aria-pressed="false"
                            style="background:#2f5d46;width:60px;height:170px" data-title="Pawn Structure Chess"
                            data-author="Andy Soltis" data-level="Intermediate" data-topic="Plans from pawn structures"
                            aria-label="Pawn Structure Chess, by Andy Soltis"><span>Pawn Structure Chess</span></button>
                    </div>
                    <div class="board"></div>
                </section>
                <section class="shelf">
                    <h2>Attack</h2>
                    <div class="books"><button class="spine" type="button" aria-pressed="false"
                            style="background:#27415e;width:56px;height:212px" data-title="The Art of Attack in Chess"
                            data-author="Vladimir Vukovic" data-level="Intermediate" data-topic="King attack"
                            aria-label="The Art of Attack in Chess, by Vladimir Vukovic"><span>The Art of Attack in
                                Chess</span></button><button class="spine" type="button" aria-pressed="false"
                            style="background:#6b4a1f;width:55px;height:177px" data-title="Zurich 1953"
                            data-author="David Bronstein" data-level="Advanced" data-topic="Annotated tournament book"
                            aria-label="Zurich 1953, by David Bronstein"><span>Zurich 1953</span></button></div>
                    <div class="board"></div>
                </section>
                <section class="reader" id="reader" aria-live="polite">
                    <div class="cover" id="r-cover"></div>
                    <div>
                        <h3 id="r-title"></h3>
                        <p class="by" id="r-by"></p>
                        <dl>
                            <dt>Level</dt>
                            <dd id="r-level"></dd>
                            <dt>Topic</dt>
                            <dd id="r-topic"></dd>
                        </dl>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <script src="assets/js/shelf.js"></script>
</body>

</html>