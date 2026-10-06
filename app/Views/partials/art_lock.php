<?php /* TFA 4 illustration: password dots fill in, the padlock opens. */ $large = $large ?? false; ?>
<div class="art art-lock<?= $large ? ' art--large' : '' ?>" data-animate aria-hidden="true">
    <?= partial('partials/lock_svg', ['class' => 'art-lock__icon']) ?>
    <div class="art-lock__field">
        <?php for ($i = 0; $i < 11; $i++): ?><span class="art-lock__dot" style="--i:<?= $i ?>"></span><?php endfor; ?>
    </div>
    <div class="art-lock__badge"><?= icon('check-circle') ?> Signed in as admin</div>
</div>
