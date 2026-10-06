<?php
/**
 * One labelled text input with its validation error.
 *
 * @var string      $name
 * @var string      $label
 * @var string      $value     already escaped
 * @var array       $errors
 * @var string      $type      text|email|password
 * @var bool        $required
 * @var string|null $hint
 * @var string|null $autocomplete
 * @var int|null    $maxlength
 */
$type         ??= 'text';
$required     ??= false;
$hint         ??= null;
$autocomplete ??= null;
$maxlength    ??= null;
$hasError     = isset($errors[$name]);
$describedBy  = trim(($hasError ? $name . '-error ' : '') . ($hint ? $name . '-hint' : ''));
$isPassword   = $type === 'password';
?>
<div class="field">
    <label class="field__label" for="<?= esc($name, 'attr') ?>"><?= esc($label) ?><?php if ($required): ?> <span class="field__req">(required)</span><?php endif; ?></label>
    <?php if ($isPassword): ?><div class="field__password"><?php endif; ?>
    <input class="field__input<?= $hasError ? ' is-invalid' : '' ?>"
           type="<?= esc($type, 'attr') ?>" id="<?= esc($name, 'attr') ?>" name="<?= esc($name, 'attr') ?>"
           value="<?= $value ?>"
           <?= $maxlength ? 'maxlength="' . (int) $maxlength . '"' : '' ?>
           <?= $autocomplete ? 'autocomplete="' . esc($autocomplete, 'attr') . '"' : '' ?>
           <?= $hasError ? 'aria-invalid="true"' : '' ?>
           <?= $describedBy !== '' ? 'aria-describedby="' . esc($describedBy, 'attr') . '"' : '' ?>>
    <?php if ($isPassword): ?>
        <button class="field__reveal" type="button" data-reveal-password="<?= esc($name, 'attr') ?>" aria-pressed="false">Show</button>
    </div>
    <?php endif; ?>
    <?php if ($hasError): ?>
        <p class="field__error" id="<?= esc($name, 'attr') ?>-error"><?= icon('alert') ?><span><?= esc($errors[$name]) ?></span></p>
    <?php endif; ?>
    <?php if ($hint): ?>
        <p class="field__hint" id="<?= esc($name, 'attr') ?>-hint"><?= esc($hint) ?></p>
    <?php endif; ?>
</div>
