<?php $home = rtrim(config('App')->baseURL, '/') . '/'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title>Something went wrong · IT0049 POS</title>
    <?php include __DIR__ . DIRECTORY_SEPARATOR . '_standalone_style.php'; ?>
</head>
<body>
    <main>
        <div class="code">500</div>
        <h1>Something went wrong.</h1>
        <p>The server couldn’t finish this request. Try again in a moment. If it keeps happening, set CI_ENVIRONMENT to development in .env to see the error.</p>
        <a href="<?= esc($home, 'attr') ?>">Go to activities</a>
    </main>
</body>
</html>
