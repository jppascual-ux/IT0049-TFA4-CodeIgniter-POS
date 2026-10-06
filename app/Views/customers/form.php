<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
    /** @var array|null $customer  null = New Customer, array = Edit Customer */
    $errors = validation_errors();
    $isEdit = $customer !== null;

    // Previously entered value (after a failed submit) > existing record value > empty.
    $value = static fn (string $field): string => esc(old($field, $customer[$field] ?? '', false) ?? '');
?>
<div class="page wrap wrap--narrow">
    <a class="back-link" href="<?= site_url('customers') ?>"><?= icon('chevron') ?> Customer Accounts</a>
    <header class="page-head">
        <div>
            <h1 class="page-title"><?= $isEdit ? 'Edit customer' : 'New customer' ?></h1>
            <p class="page-sub"><?= $isEdit ? 'Changes are saved to customer #' . esc($customer['id']) . '.' : 'Full name and email are required.' ?></p>
        </div>
    </header>

    <?= partial('partials/error_summary', ['errors' => $errors]) ?>

    <?= form_open($action, ['class' => 'form-card', 'novalidate' => true, 'data-loading' => '']) ?>
        <?= partial('partials/field', ['name' => 'full_name', 'label' => 'Full name', 'value' => $value('full_name'), 'errors' => $errors, 'required' => true, 'maxlength' => 100, 'autocomplete' => 'name']) ?>
        <?= partial('partials/field', ['name' => 'email', 'label' => 'Email', 'type' => 'email', 'value' => $value('email'), 'errors' => $errors, 'required' => true, 'maxlength' => 100, 'autocomplete' => 'email']) ?>
        <?= partial('partials/field', ['name' => 'phone', 'label' => 'Phone', 'value' => $value('phone'), 'errors' => $errors, 'maxlength' => 20, 'autocomplete' => 'tel', 'hint' => 'Optional. Numbers, spaces, +, - and parentheses.']) ?>

        <div class="form-actions">
            <a class="btn btn--quiet" href="<?= site_url('customers') ?>">Cancel</a>
            <button type="submit" class="btn"><?= $isEdit ? 'Save changes' : 'Add customer' ?></button>
        </div>
    <?= form_close() ?>
</div>
<?= $this->endSection() ?>
