<?php
    // Standalone on purpose: it must render even when the app itself fails to load.
    $home = rtrim(config('App')->baseURL, '/') . '/';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>Page not found · IT0049 POS</title>
    <?php include __DIR__ . DIRECTORY_SEPARATOR . '_standalone_style.php'; ?>
</head>
<body>
    <main>
        <div class="code">404</div>
        <h1>This page doesn’t exist.</h1>
        <p>
            <?php if (ENVIRONMENT !== 'production' && ! empty($message) && $message !== '(null)'): ?>
                <?= nl2br(esc($message)) ?>
            <?php else: ?>
                Check the address, or start again from the list of activities.
            <?php endif; ?>
        </p>
        <a href="<?= esc($home, 'attr') ?>">Go to activities</a>
    </main>
</body>
</html>
