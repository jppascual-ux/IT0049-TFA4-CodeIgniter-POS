<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
    use App\Models\UserModel;

    /** @var array|null $user  null = New User, array = Edit User */
    $errors = validation_errors();
    $isEdit = $user !== null;

    // Previously entered value (after a failed submit) > existing record value > empty.
    $value = static fn (string $field): string => esc(old($field, $user[$field] ?? '', false) ?? '');
?>
<div class="page wrap wrap--narrow">
    <a class="back-link" href="<?= site_url('users') ?>"><?= icon('chevron') ?> User Accounts</a>
    <header class="page-head">
        <div>
            <h1 class="page-title"><?= $isEdit ? 'Edit user' : 'New user' ?></h1>
            <p class="page-sub"><?= $isEdit ? 'Update @' . esc($user['username']) . ', their password or their profile picture.' : 'The new user can log in as soon as you save.' ?></p>
        </div>
    </header>

    <?= partial('partials/error_summary', ['errors' => $errors]) ?>

    <?= form_open_multipart($action, ['class' => 'form-card', 'novalidate' => true, 'data-loading' => '']) ?>
        <?php if ($isEdit): ?>
            <div class="field">
                <span class="field__label" id="avatar-label">Profile picture</span>
                <div class="avatar-field">
                    <img class="avatar-field__preview" src="<?= esc(UserModel::avatarUrl($user['avatar'] ?? null)) ?>" alt="Current profile picture" data-avatar-preview>
                    <div>
                        <input class="file-input" type="file" id="avatar" name="avatar" accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                               data-avatar-input aria-labelledby="avatar-label"<?= isset($errors['avatar']) ? ' aria-invalid="true" aria-describedby="avatar-error"' : '' ?>>
                        <label class="dropzone<?= isset($errors['avatar']) ? ' is-invalid' : '' ?>" for="avatar" data-avatar-zone>
                            <?= icon('upload') ?>
                            <span class="dropzone__title">Choose a photo</span>
                            <span class="dropzone__hint">or drag it here. JPG or PNG, up to 2 MB.</span>
                            <span class="dropzone__file" data-avatar-name></span>
                        </label>
                        <p class="client-note" data-avatar-note aria-live="polite"></p>
                        <?php if (isset($errors['avatar'])): ?>
                            <p class="field__error" id="avatar-error"><?= icon('alert') ?><span><?= esc($errors['avatar']) ?></span></p>
                        <?php endif; ?>
                        <p class="field__hint">Cropped to a 200 × 200 thumbnail. Leave empty to keep the current picture.</p>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="form-split">
            <?= partial('partials/field', ['name' => 'username', 'label' => 'Username', 'value' => $value('username'), 'errors' => $errors, 'required' => true, 'maxlength' => 50, 'autocomplete' => 'off', 'hint' => 'Must be unique. Letters, numbers, - and _ only.']) ?>
            <?= partial('partials/field', ['name' => 'full_name', 'label' => 'Full name', 'value' => $value('full_name'), 'errors' => $errors, 'required' => true, 'maxlength' => 100, 'autocomplete' => 'off']) ?>
        </div>

        <div class="form-section">
            <p class="form-section__title">Password</p>
            <p class="form-section__text"><?= $isEdit ? 'Leave both boxes empty to keep the current password.' : 'At least 8 characters. Saved as a secure hash, never as plain text.' ?></p>
        </div>

        <div class="form-split">
            <?= partial('partials/field', ['name' => 'password', 'label' => $isEdit ? 'New password' : 'Password', 'type' => 'password', 'value' => '', 'errors' => $errors, 'required' => ! $isEdit, 'maxlength' => 72, 'autocomplete' => 'new-password']) ?>
            <?= partial('partials/field', ['name' => 'password_confirm', 'label' => 'Confirm password', 'type' => 'password', 'value' => '', 'errors' => $errors, 'required' => ! $isEdit, 'maxlength' => 72, 'autocomplete' => 'new-password']) ?>
        </div>

        <div class="form-actions">
            <a class="btn btn--quiet" href="<?= site_url('users') ?>">Cancel</a>
            <button type="submit" class="btn"><?= $isEdit ? 'Save changes' : 'Add user' ?></button>
        </div>
    <?= form_close() ?>
</div>
<?= $this->endSection() ?>
