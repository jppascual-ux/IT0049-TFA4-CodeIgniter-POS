<?= $this->extend('layouts/main') ?>

<?= $this->section('bodyClass') ?>is-auth<?= $this->endSection() ?>

<?= $this->section('content') ?>
<?php
    $errors    = validation_errors();
    $error     = session()->getFlashdata('error');
    $loggedOut = service('request')->getGet('logged_out') !== null;
?>
<div class="auth wrap">
    <div class="auth__inner">
        <div class="auth__head">
            <?= partial('partials/lock_svg', ['class' => 'auth__lock']) ?>
            <h1 class="auth__title">Log in to POS</h1>
            <p class="auth__text">Customer and user records are for staff only.</p>
        </div>

        <?php if ($loggedOut): ?>
            <div class="notice notice--success" role="status"><?= icon('check-circle') ?><span>You’re logged out. Your session was deleted.</span></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="notice notice--error" role="alert"><?= icon('alert') ?><span><?= esc($error) ?></span></div>
        <?php endif; ?>

        <?= form_open('login', ['class' => 'form-card', 'novalidate' => true, 'data-loading' => '']) ?>
            <?= partial('partials/field', ['name' => 'username', 'label' => 'Username', 'value' => esc(old('username', '', false) ?? ''), 'errors' => $errors, 'autocomplete' => 'username', 'maxlength' => 50]) ?>
            <?= partial('partials/field', ['name' => 'password', 'label' => 'Password', 'type' => 'password', 'value' => '', 'errors' => $errors, 'autocomplete' => 'current-password', 'maxlength' => 72]) ?>
            <div class="form-actions">
                <button type="submit" class="btn btn--block">Log in</button>
            </div>
        <?= form_close() ?>

        <div class="demo">
            <p class="demo__text">Demo account <strong>admin</strong> / <strong>password123</strong></p>
            <button class="btn btn--quiet btn--sm" type="button" data-demo-fill data-user="admin" data-pass="password123">Fill in</button>
        </div>

        <p class="auth__back"><a href="<?= site_url('/') ?>">Back to activities</a></p>
    </div>
</div>
<?= $this->endSection() ?>
