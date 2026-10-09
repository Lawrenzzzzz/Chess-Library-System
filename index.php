<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';

$user = current_user();
$googleEnabled = !empty(cfg('google.client_id'));
$flash = '';
if (($_GET['auth_error'] ?? '') === 'google') $flash = 'Google sign-in could not be completed. Please try again.';
if (($_GET['verified'] ?? '') === '1') $flash = '';
$verifiedNotice = ($_GET['verified'] ?? '') === '1' ? 'Your email is confirmed. You can sign in now.' : '';

page_head(
    'Chess Library System — Your next move starts here',
    'Discover a home for chess books, game studies, and lifelong learning. Sign in or create your Chess Library member account.'
);
?>
<div class="site">
  <header class="site-header">
    <div class="header-inner">
      <a href="<?= e(url('/')) ?>" class="brand" aria-label="Chess Library home">
        <span class="brand-mark">♞</span>
        <span>
          Chess Library
          <span class="brand-subtitle">LEARN. EXPLORE. ADVANCE.</span>
        </span>
      </a>
      <span class="header-note">A home for the thoughtful player.</span>
      </button>
    </div>
  </header>

  <main>
    <section class="welcome">
      <img class="welcome-image" src="<?= e(url('assets/chess-library.jpg')) ?>"
           alt="A carved wooden knight on a chessboard beside a collection of books"
           width="1536" height="1024" fetchpriority="high">
      <div class="welcome-shade"></div>
      <div class="welcome-inner">
        <div class="welcome-copy">
          <div class="eyebrow"><span></span>THE CHESS LIBRARY SYSTEM</div>
          <h1>Chess Library.<br>A world of knowledge.<br><em>A better next move.</em></h1>
          <p>Every great move begins with a little knowledge. A dedicated home for chess books, timeless strategies, and the ideas that help you grow.</p>
          <div class="welcome-caption">
            <?= icon('book-open', 18) ?>
            <span>For curious beginners. For lifelong players.</span>
          </div>
        </div>

        <!-- Member panel -->
        <div class="member-panel" id="member-access">
        <?php if ($user): ?>
          <div class="signed-in">
            <span class="panel-symbol"><?= icon('check-circle') ?></span>
            <span class="section-kicker">YOUR MEMBER ACCOUNT</span>
            <h2>Welcome, <?= e($user['display_name'] ?: 'chess player') ?>.</h2>
            <p>You’re signed in to Chess Library.</p>
            <div class="account-email"><?= icon('mail', 17) ?><?= e($user['email']) ?></div>
            <button type="button" id="signout" class="btn btn-outline form-button">
              <?= icon('log-out') ?>Sign out
            </button>
          </div>
        <?php else: ?>
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
                  <?= icon('user-round') ?>
                  <input id="name" name="name" autocomplete="name" maxlength="100" placeholder="Your name" required>
                </div>
              </label>

              <label for="email">
                Email address
                <div class="input-wrap">
                  <?= icon('mail') ?>
                  <input id="email" name="email" type="email" autocomplete="email" maxlength="255" placeholder="you@example.com" required>
                </div>
              </label>

              <label for="password" data-modes="login signup">
                <span class="label-row">
                  Password
                  <button type="button" class="btn btn-link forgot-button" data-mode-btn="forgot" data-modes="login">Forgot password?</button>
                </span>
                <div class="input-wrap">
                  <?= icon('lock-keyhole') ?>
                  <input id="password" name="password" type="password" autocomplete="current-password"
                         maxlength="128" placeholder="Enter your password" required>
                  <button type="button" class="btn btn-ghost btn-icon password-toggle" id="pw-toggle"
                          title="Show password" aria-label="Show password">
                    <span class="pw-show"><?= icon('eye') ?></span>
                    <span class="pw-hide" hidden><?= icon('eye-off') ?></span>
                  </button>
                </div>
              </label>

              <p class="form-notice" id="form-notice" role="status" hidden></p>

              <button class="btn btn-default form-button" type="submit" id="submit-btn">
                <span class="btn-label">Sign in to the library</span>
                <?= icon('arrow-right', 0, 'btn-arrow') ?>
                <?= icon('loader', 0, 'btn-spinner animate-spin') ?>
              </button>
            </form>

            <div data-modes="login signup">
              <?php if ($googleEnabled): ?>
              <div class="or-divider"><span></span>or continue with<span></span></div>
              <a class="btn btn-outline form-button google-button" href="<?= e(url('google.php')) ?>">
                <span class="google-mark">G</span>Google
              </a>
              <?php endif; ?>
              <p class="membership-note">
                <span id="note-text">New to the library?</span>
                <button type="button" class="btn btn-link" id="note-switch">Join our community</button>
              </p>
            </div>

            <button type="button" class="btn btn-link back-signin" data-mode-btn="login" data-modes="forgot" hidden>
              Back to sign in
            </button>
          </div>
        <?php endif; ?>
          <p class="form-error" id="form-error" role="alert" <?= $flash ? '' : 'hidden' ?>><?= e($flash) ?></p>
          <?php if ($verifiedNotice && !$user): ?><p class="form-notice" style="margin-top:12px"><?= e($verifiedNotice) ?></p><?php endif; ?>
          <div class="secure-note"><?= icon('lock-keyhole', 12) ?>A secure space for your chess journey.</div>
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
          <span class="feature-icon"><?= icon('library') ?></span>
          <div>
            <h3>Knowledge worth exploring</h3>
            <p>Chess literature, classic ideas, and fresh perspectives—all with a place on the shelf.</p>
          </div>
          <span class="feature-number">01</span>
        </article>
        <article>
          <span class="feature-icon"><?= icon('book-open') ?></span>
          <div>
            <h3>Study the great moves</h3>
            <p>Discover the thinking behind memorable games, from opening plans to endgame mastery.</p>
          </div>
          <span class="feature-number">02</span>
        </article>
        <article>
          <span class="feature-icon"><?= icon('graduation-cap') ?></span>
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
    <span><?= icon('shield-check', 14) ?> Your knowledge journey starts here.</span>
  </footer>
</div>
<?php page_foot(); ?>
