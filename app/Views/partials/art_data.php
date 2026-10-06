<?php /* TFA 2 illustration: PHP array lines turn into database rows. */ $large = $large ?? false; ?>
<div class="art art-data<?= $large ? ' art--large' : '' ?>" data-animate aria-hidden="true">
    <div class="art-data__window">
        <div class="art-data__bar"><?= icon('database', 'art-data__db') ?> customers</div>
        <?php foreach (["['Maria Santos', 'maria@…'],", "['Jose Reyes', 'jose@…'],", "['Ana Cruz', 'ana@…'],", "['Carlo Dizon', 'carlo@…'],"] as $i => $line): ?>
            <div class="art-data__row" style="--i:<?= $i ?>">
                <div class="art-data__src"><?= esc($line) ?></div>
                <div class="art-data__cells">
                    <span class="art-data__dot"></span>
                    <span class="art-data__line art-data__line--a"></span>
                    <span class="art-data__line art-data__line--b"></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
