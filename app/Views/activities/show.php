<?= $this->extend('layouts/main') ?>

<?= $this->section('bodyClass') ?>is-marketing<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
    $theme = $activity['theme'];
    $isTfa4 = $activity['number'] === 4;
?>
<section class="activity-hero activity-hero--<?= esc($theme, 'attr') ?>">
    <div class="wrap">
        <p class="hero__kicker hue hue--<?= esc($theme, 'attr') ?> rise" style="--i:0">TFA <?= $activity['number'] ?></p>
        <h1 class="hero__title rise" style="--i:1"><?= esc($activity['title']) ?></h1>
        <p class="hero__lead rise" style="--i:2"><?= esc($activity['summary']) ?></p>
        <div class="activity-hero__art rise" style="--i:3"><?= partial($activity['art'], ['large' => true]) ?></div>
    </div>
</section>

<section class="section">
    <div class="wrap wrap--text">
        <h2 class="section__title" data-reveal>Try it</h2>
        <p class="section__lead" data-reveal><?= esc($activity['tagline']) ?>
            <?php if (! $isLoggedIn): ?> Customer and user pages ask you to log in first.<?php endif; ?></p>
        <div class="section__body group" data-reveal>
            <?php foreach ($activity['actions'] as $action): ?>
                <a class="group__row" href="<?= site_url($action['url']) ?>">
                    <span class="group__icon group__icon--<?= esc($theme, 'attr') ?>"><?= icon($action['icon']) ?></span>
                    <span class="group__main">
                        <span class="group__title"><?= esc($action['label']) ?></span>
                        <span class="group__text"><?= esc($action['text']) ?></span>
                    </span>
                    <span class="group__trail">
                        <?php if (! $isLoggedIn && empty($action['public'])): ?>
                            <span class="badge"><?= icon('lock') ?> Log in</span>
                        <?php endif; ?>
                        <?= icon('chevron') ?>
                    </span>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php if ($isTfa4): ?>
<section class="section section--mist" id="session">
    <div class="wrap wrap--text">
        <h2 class="section__title" data-reveal>Your session right now</h2>
        <p class="section__lead" data-reveal>Read back from the session on this request. Log in or out and come back to see it change.</p>
        <div class="section__body session-panel" data-reveal>
            <div class="session-state<?= $session['isLoggedIn'] ? ' is-on' : '' ?>">
                <div class="session-state__ring"><?= icon($session['isLoggedIn'] ? 'unlock' : 'lock') ?></div>
                <p class="session-state__title"><?= $session['isLoggedIn'] ? 'Logged in' : 'Logged out' ?></p>
                <p class="session-state__text">
                    <?= $session['isLoggedIn']
                        ? 'Protected pages will load for ' . esc($session['username']) . '.'
                        : 'Protected pages will send you to the login page.' ?>
                </p>
                <?php if ($session['isLoggedIn']): ?>
                    <?= form_open('logout') ?><button class="btn btn--quiet btn--sm" type="submit">Log out</button><?= form_close() ?>
                <?php else: ?>
                    <a class="btn btn--sm" href="<?= site_url('login') ?>">Log in</a>
                <?php endif; ?>
            </div>
            <div class="group">
                <?php
                    $rows = [
                        'isLoggedIn'   => $session['isLoggedIn'] ? 'true' : 'false',
                        'user_id'      => $session['user_id'] ?? '—',
                        'username'     => $session['username'] ?? '—',
                        'full_name'    => $session['full_name'] ?? '—',
                        'logged_in_at' => $session['logged_in_at'] ?? '—',
                        'session id'   => $session['session_id'] ?? '—',
                    ];
                ?>
                <dl class="kv">
                    <?php foreach ($rows as $key => $val): ?>
                        <div class="group__row kv__row">
                            <dt><?= esc($key) ?></dt>
                            <dd><?= esc((string) $val) ?></dd>
                        </div>
                    <?php endforeach; ?>
                </dl>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="section<?= $isTfa4 ? '' : ' section--mist' ?>">
    <div class="wrap wrap--text">
        <h2 class="section__title" data-reveal>What it teaches</h2>
        <p class="section__lead" data-reveal>The learning outcomes on the activity sheet, and where each one lives in the code.</p>
        <div class="section__body group" data-reveal>
            <?php foreach ($activity['outcomes'] as $outcome): ?>
                <div class="group__row">
                    <span class="group__icon group__icon--done"><?= icon('check') ?></span>
                    <span class="group__main">
                        <span class="group__title"><?= esc($outcome['text']) ?></span>
                        <span class="group__text"><code><?= esc($outcome['where']) ?></code></span>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section<?= $isTfa4 ? ' section--mist' : '' ?>">
    <div class="wrap wrap--text">
        <h2 class="section__title" data-reveal>Requirements met</h2>
        <p class="section__lead" data-reveal>Each instruction from the laboratory activity, with where to see it.</p>
        <div class="section__body group" data-reveal>
            <?php foreach ($activity['requirements'] as $req): ?>
                <?php $tag = $req['url'] ? 'a' : 'div'; ?>
                <<?= $tag ?> class="group__row"<?= $req['url'] ? ' href="' . site_url($req['url']) . '"' : '' ?>>
                    <span class="group__icon group__icon--done"><?= icon('check') ?></span>
                    <span class="group__main">
                        <span class="group__title"><?= esc($req['text']) ?></span>
                        <span class="group__text"><?= esc($req['evidence']) ?></span>
                    </span>
                    <?php if ($req['url']): ?><span class="group__trail"><?= icon('chevron') ?></span><?php endif; ?>
                </<?= $tag ?>>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<section class="section<?= $isTfa4 ? '' : ' section--mist' ?>">
    <div class="wrap">
        <h2 class="section__title" data-reveal>How it works</h2>
        <p class="section__lead" data-reveal>The key lines, taken from this project.</p>
        <div class="section__body code-stack code-stack--two">
            <?php foreach ($activity['code'] as $i => $snippet): ?>
                <figure class="code-card" data-reveal style="--i:<?= $i ?>">
                    <figcaption class="code-card__file"><?= esc($snippet['file']) ?></figcaption>
                    <pre><code><?= esc($snippet['code']) ?></code></pre>
                </figure>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<nav class="wrap pager" aria-label="Other activities">
    <?php if ($previous): ?>
        <a class="pager__link" href="<?= site_url($previous['slug']) ?>">
            <span class="pager__hint">Previous</span>
            <span class="pager__title">TFA <?= $previous['number'] ?>: <?= esc($previous['title']) ?></span>
        </a>
    <?php else: ?>
        <a class="pager__link" href="<?= site_url('/') ?>">
            <span class="pager__hint">Back to</span>
            <span class="pager__title">All activities</span>
        </a>
    <?php endif; ?>
    <?php if ($next): ?>
        <a class="pager__link pager__link--next" href="<?= site_url($next['slug']) ?>">
            <span class="pager__hint">Next</span>
            <span class="pager__title">TFA <?= $next['number'] ?>: <?= esc($next['title']) ?></span>
        </a>
    <?php else: ?>
        <a class="pager__link pager__link--next" href="<?= site_url('/') ?>">
            <span class="pager__hint">Back to</span>
            <span class="pager__title">All activities</span>
        </a>
    <?php endif; ?>
</nav>
<?= $this->endSection() ?>
