<?php if (! empty($errors)): ?>
    <div class="notice notice--error" role="alert">
        <?= icon('alert') ?>
        <div>
            <strong><?= count($errors) === 1 ? 'Fix 1 field to continue.' : 'Fix ' . count($errors) . ' fields to continue.' ?></strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
<?php endif; ?>
