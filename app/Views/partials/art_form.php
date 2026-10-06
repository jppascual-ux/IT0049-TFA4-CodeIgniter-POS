<?php /* TFA 3 illustration: a typo is caught by validation, fixed, then saved. */ $large = $large ?? false; ?>
<div class="art art-form<?= $large ? ' art--large' : '' ?>" data-animate aria-hidden="true">
    <div class="art-form__toast"><?= icon('check-circle') ?> Customer added</div>
    <div class="art-form__card">
        <div class="art-form__label">Email</div>
        <div class="art-form__field">
            <span class="art-form__typed">juan.delacruz</span><span class="art-form__fix">@email.com</span><span class="art-form__caret"></span>
            <?= icon('check', 'art-form__check') ?>
        </div>
        <div class="art-form__error">Enter a valid email address.</div>
        <div class="art-form__btn">Add customer</div>
    </div>
</div>
