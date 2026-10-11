<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Chess Library System — Your next move starts here</title>
  <meta name="description" content="Discover a home for chess books, game studies, and lifelong learning.">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;450;500;550;600;650;700&family=Libre+Caslon+Display&display=swap">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="icon" href="favicon.ico" type="image/x-icon">
</head>
<body>
  <div class="site">
    <header class="site-header">
      <div class="header-inner">
        <a href="/" class="brand" aria-label="Chess Library home">
          <span class="brand-mark">♞</span>
          <span>
            Chess Library
            <span class="brand-subtitle">LEARN. EXPLORE. ADVANCE.</span>
          </span>
        </a>
        <span class="header-note">A home for the thoughtful player.</span>
      </div>
    </header>

    <main>
      <section class="welcome">
        <img class="welcome-image" src="assets/chess-library.jpg" alt="A carved wooden knight on a chessboard beside a collection of books" width="1536" height="1024" fetchpriority="high">
        <div class="welcome-shade"></div>
        <div class="welcome-inner">
          <div class="welcome-copy">
            <div class="eyebrow"><span></span>THE CHESS LIBRARY SYSTEM</div>
            <h1>Chess Library.<br>A world of knowledge.<br><em>A better next move.</em></h1>
            <p>Every great move begins with a little knowledge. A dedicated home for chess books, timeless strategies, and the ideas that help you grow.</p>
            <div class="welcome-caption">
              <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 7v14"/><path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"/></svg>
              <span>For curious beginners. For lifelong players.</span>
            </div>
          </div>

          <!-- Member panel -->
          <div class="member-panel" id="member-access">
            <div id="auth-guest" data-mode="login">
              <span class="panel-symbol">♞</span>
              <h2 id="panel-title">Welcome back.</h2>
              <p class="panel-intro" id="panel-intro">Your next chapter in chess starts here.</p>

              <div class="auth-tabs" data-modes="login signup">
                <button type="button" class="btn btn-ghost auth-tab active" data-mode-btn="login">Sign in</button>
                <button type="button" class="btn btn-ghost auth-tab" data-mode-btn="signup">Create account</button>
              </div>

              <form id="member-form" class="member-fields">
                <label for="name" data-modes="signup" hidden>
                  Full name
                  <div class="input-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 0 0-16 0"/></svg>
                    <input id="name" name="name" autocomplete="name" maxlength="100" placeholder="Your name">
                  </div>
                </label>

                <label for="email">
                  Email address
                  <div class="input-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m22 7-8.991 5.727a2 2 0 0 1-2.009 0L2 7"/><rect x="2" y="4" width="20" height="16" rx="2"/></svg>
                    <input id="email" name="email" type="email" autocomplete="email" maxlength="255" placeholder="you@example.com" required>
                  </div>
                </label>

                <label for="password" data-modes="login signup">
                  <span class="label-row">
                    Password
                    <button type="button" class="btn btn-link forgot-button" data-mode-btn="forgot" data-modes="login">Forgot password?</button>
                  </span>
                  <div class="input-wrap">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="16" r="1"/><rect x="3" y="10" width="18" height="12" rx="2"/><path d="M7 10V7a5 5 0 0 1 10 0v3"/></svg>
                    <input id="password" name="password" type="password" autocomplete="current-password" maxlength="128" placeholder="Enter your password" required>
                  </div>
                </label>

                <button class="btn btn-default form-button" type="submit" id="submit-btn">
                  <span class="btn-label">Sign in to the library</span>
                </button>
              </form>

              <div data-modes="login signup">
                <div class="or-divider"><span></span>or continue with<span></span></div>
                <button class="btn btn-outline form-button google-button" type="button">
                  <span class="google-mark">G</span>Google
                </button>
                <p class="membership-note">
                  <span id="note-text">New to the library?</span>
                  <button type="button" class="btn btn-link" id="note-switch">Join our community</button>
                </p>
              </div>
            </div>
            <div class="secure-note">A secure space for your chess journey.</div>
          </div>
        </div>
        <div class="hero-bottom">
          <span>THE GAME IS TIMELESS. THE LEARNING NEVER STOPS.</span>
          <span class="hero-coordinate">a b c d e f g h</span>
        </div>
      </section>

      <section class="possibilities">
        <div class="section-heading">
          <span class="section-kicker">MORE THAN A COLLECTION</span>
          <h2>A place to deepen your game.</h2>
          <p>From your first opening to your next breakthrough.</p>
        </div>
        <div class="feature-grid">
          <article>
            <span class="feature-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m16 6 4 14"/><path d="M12 6v14"/><path d="M8 8v12"/><path d="M4 4v16"/></svg>
            </span>
            <div>
              <h3>Knowledge worth exploring</h3>
              <p>Chess literature, classic ideas, and fresh perspectives—all with a place on the shelf.</p>
            </div>
            <span class="feature-number">01</span>
          </article>
          <article>
            <span class="feature-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 7v14"/><path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"/></svg>
            </span>
            <div>
              <h3>Study the great moves</h3>
              <p>Discover the thinking behind memorable games, from opening plans to endgame mastery.</p>
            </div>
            <span class="feature-number">02</span>
          </article>
          <article>
            <span class="feature-icon">
              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21.42 10.922a1 1 0 0 0-.019-1.838L12.83 5.18a2 2 0 0 0-1.66 0L2.6 9.08a1 1 0 0 0 0 1.832l8.57 3.908a2 2 0 0 0 1.66 0z"/><path d="M22 10v6"/><path d="M6 12.5V16a6 3 0 0 0 12 0v-3.5"/></svg>
            </span>
            <div>
              <h3>Grow at your own pace</h3>
              <p>A welcoming space for every level, because there is always something new to learn.</p>
            </div>
            <span class="feature-number">03</span>
          </article>
        </div>
      </section>
    </main>

    <footer class="site-footer">
      <span>♞ <strong>Chess Library System</strong><span class="footer-divider">/</span>A little study. A better game.</span>
      <span>
        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
        Your knowledge journey starts here.
      </span>
    </footer>
  </div>
  <script src="assets/js/app.js" defer></script>
</body>
</html>