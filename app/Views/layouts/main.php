<?php
    use App\Models\UserModel;

    $uri        = uri_string();
    $isLoggedIn = session()->get('isLoggedIn') === true;
    $bodyClass  = trim($this->renderSection('bodyClass', true)) ?: 'is-app';
    $success    = session()->getFlashdata('success');

    $navLinks = [
        ['label' => 'Activities',  'url' => '/',         'active' => $uri === '' || str_starts_with($uri, 'tfa')],
        ['label' => 'Customers',   'url' => 'customers', 'active' => str_starts_with($uri, 'customers')],
        ['label' => 'Users',       'url' => 'users',     'active' => str_starts_with($uri, 'users')],
    ];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="theme-color" content="#ffffff" media="(prefers-color-scheme: light)">
    <meta name="theme-color" content="#000000" media="(prefers-color-scheme: dark)">
    <meta name="description" content="IT0049 Point of Sale — CodeIgniter 4 activities TFA 2 to TFA 4.">
    <title><?= esc($title ?? 'POS') ?> · IT0049 POS</title>
    <link rel="icon" href="<?= base_url('assets/img/favicon.svg') ?>" type="image/svg+xml">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="<?= base_url('assets/css/app.css') ?>">
    <script>document.documentElement.classList.add('js');</script>
    <script src="<?= base_url('assets/js/app.js') ?>" defer></script>
</head>
<body class="<?= esc($bodyClass, 'attr') ?>">
<a class="skip-link" href="#main">Skip to content</a>

<header class="nav">
    <div class="nav__inner">
        <a class="brand" href="<?= site_url('/') ?>" aria-label="IT0049 POS home">
            <?= brand_mark() ?>
            <span>IT0049 POS</span>
        </a>

        <nav class="nav__links" aria-label="Main">
            <?php foreach ($navLinks as $link): ?>
                <a class="nav__link<?= $link['active'] ? ' is-active' : '' ?>" href="<?= site_url($link['url']) ?>"<?= $link['active'] ? ' aria-current="page"' : '' ?>><?= esc($link['label']) ?></a>
            <?php endforeach; ?>

            <div class="nav__account">
                <?php if ($isLoggedIn): ?>
                    <span class="nav__user">
                        <img class="nav__avatar" src="<?= esc(UserModel::avatarUrl(session()->get('avatar'))) ?>" alt="">
                        <span><?= esc(session()->get('username')) ?></span>
                    </span>
                    <?= form_open('logout') ?>
                        <button type="submit" class="btn btn--quiet btn--sm">Log out</button>
                    <?= form_close() ?>
                <?php else: ?>
                    <a class="btn btn--sm" href="<?= site_url('login') ?>">Log in</a>
                <?php endif; ?>
            </div>
        </nav>

        <button class="nav__toggle" type="button" data-menu-toggle aria-expanded="false" aria-controls="sheet" aria-label="Open menu">
            <span></span><span></span>
        </button>
    </div>
</header>

<div class="sheet" id="sheet">
    <div class="sheet__group">
        <p class="sheet__heading">Activities</p>
        <a class="sheet__link" style="--i:0" href="<?= site_url('/') ?>">All activities</a>
        <a class="sheet__link" style="--i:1" href="<?= site_url('tfa2') ?>">TFA 2: Database</a>
        <a class="sheet__link" style="--i:2" href="<?= site_url('tfa3') ?>">TFA 3: Forms</a>
        <a class="sheet__link" style="--i:3" href="<?= site_url('tfa4') ?>">TFA 4: Login</a>
    </div>
    <div class="sheet__group">
        <p class="sheet__heading">Records</p>
        <a class="sheet__link" style="--i:4" href="<?= site_url('customers') ?>">Customer Accounts</a>
        <a class="sheet__link" style="--i:5" href="<?= site_url('users') ?>">User Accounts</a>
    </div>
    <div class="sheet__group">
        <p class="sheet__heading"><?= $isLoggedIn ? 'Signed in as ' . esc(session()->get('username')) : 'Account' ?></p>
        <?php if ($isLoggedIn): ?>
            <?= form_open('logout') ?>
                <button type="submit" class="sheet__link sheet__link--small" style="--i:6">Log out</button>
            <?= form_close() ?>
        <?php else: ?>
            <a class="sheet__link sheet__link--small" style="--i:6" href="<?= site_url('login') ?>">Log in</a>
        <?php endif; ?>
    </div>
</div>

<?php if ($success): ?>
    <div class="island" role="status" aria-live="polite" data-island title="Dismiss">
        <?= icon('check-circle') ?>
        <span><?= esc($success) ?></span>
    </div>
<?php endif; ?>

<main id="main">
    <?= $this->renderSection('content') ?>
</main>

<footer class="footer">
    <div class="wrap footer__inner">
        <p>IT0049 Web System Technologies. Built with CodeIgniter 4 and MySQL.</p>
        <nav class="footer__links" aria-label="Activities">
            <a href="<?= site_url('tfa2') ?>">TFA 2</a>
            <a href="<?= site_url('tfa3') ?>">TFA 3</a>
            <a href="<?= site_url('tfa4') ?>">TFA 4</a>
            <a href="<?= site_url('login') ?>">Log in</a>
        </nav>
    </div>
</footer>
</body>
</html>
