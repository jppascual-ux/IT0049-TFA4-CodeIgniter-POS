<?= $this->extend('layouts/main') ?>

<?= $this->section('bodyClass') ?>is-marketing<?= $this->endSection() ?>

<?= $this->section('content') ?>
<section class="hero wrap">
    <h1 class="hero__title rise" style="--i:0">Point of sale, built one activity at a time.</h1>
    <p class="hero__lead rise" style="--i:1">A CodeIgniter 4 app for IT0049. Choose an activity to see what it added, then try it live.</p>
    <div class="hero__actions rise" style="--i:2">
        <?php if ($isLoggedIn): ?>
            <a class="btn" href="<?= site_url('customers') ?>">Open Customer Accounts</a>
        <?php else: ?>
            <a class="btn" href="<?= site_url('login') ?>">Log in</a>
        <?php endif; ?>
        <a class="btn btn--ghost" href="#activities">Choose an activity</a>
    </div>
    <?php if (! $isLoggedIn): ?>
        <p class="hero__note rise" style="--i:3">Demo account: <strong>admin</strong> / <strong>password123</strong></p>
    <?php endif; ?>
</section>

<section class="wrap" id="activities" aria-label="Activities">
    <div class="bento">
        <?php $tfa4 = $activities[4]; ?>
        <article class="tile tile--wide tile--dark" data-reveal>
            <p class="tile__label hue hue--access">TFA 4</p>
            <h2 class="tile__title"><?= esc($tfa4['title']) ?></h2>
            <p class="tile__text">Staff log in with a hashed password. A filter keeps everyone else out of customer and user records.</p>
            <div class="tile__actions">
                <a class="btn btn--light" href="<?= site_url('tfa4') ?>">Explore TFA 4</a>
                <?php if ($isLoggedIn): ?>
                    <a class="btn btn--ghost-light" href="<?= site_url('tfa4') ?>#session">See your session</a>
                <?php else: ?>
                    <a class="btn btn--ghost-light" href="<?= site_url('login') ?>">Log in</a>
                <?php endif; ?>
            </div>
            <div class="tile__art"><?= partial($tfa4['art']) ?></div>
        </article>

        <?php $tfa3 = $activities[3]; ?>
        <article class="tile" data-reveal style="--i:1">
            <p class="tile__label hue hue--form">TFA 3</p>
            <h2 class="tile__title"><?= esc($tfa3['title']) ?></h2>
            <p class="tile__text">Validated forms for new and existing records, plus profile pictures resized into thumbnails.</p>
            <div class="tile__actions">
                <a class="btn" href="<?= site_url('tfa3') ?>">Explore TFA 3</a>
                <a class="btn btn--ghost" href="<?= site_url('customers/new') ?>">New customer</a>
            </div>
            <div class="tile__art"><?= partial($tfa3['art']) ?></div>
        </article>

        <?php $tfa2 = $activities[2]; ?>
        <article class="tile" data-reveal style="--i:2">
            <p class="tile__label hue hue--data">TFA 2</p>
            <h2 class="tile__title"><?= esc($tfa2['title']) ?></h2>
            <p class="tile__text">Customer and user records moved out of PHP arrays and into MySQL, read through CodeIgniter Models.</p>
            <div class="tile__actions">
                <a class="btn" href="<?= site_url('tfa2') ?>">Explore TFA 2</a>
                <a class="btn btn--ghost" href="<?= site_url('customers') ?>">Customer Accounts</a>
            </div>
            <div class="tile__art"><?= partial($tfa2['art']) ?></div>
        </article>
    </div>

    <p class="home-foot">Customer and user pages need a login since TFA 4. Every sample account uses the password <strong>password123</strong>.</p>
</section>
<?= $this->endSection() ?>
