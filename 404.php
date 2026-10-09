<?php
declare(strict_types=1);
require __DIR__ . '/includes/bootstrap.php';
http_response_code(404);
page_head('Page not found — Chess Library', 'The page you’re looking for doesn’t exist or has been moved.');
?>
<div class="notfound">
  <div class="notfound-inner">
    <h1>404</h1>
    <h2>Page not found</h2>
    <p>The page you're looking for doesn't exist or has been moved.</p>
    <a class="btn btn-default" href="<?= e(url('/')) ?>">Go home</a>
  </div>
</div>
<?php page_foot(); ?>
