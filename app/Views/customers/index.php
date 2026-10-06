<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
    $count   = count($customers);
    $summary = $count === 1 ? '1 customer in the database' : $count . ' customers in the database';
    $newestId = $count ? max(array_column($customers, 'id')) : null;
    $justSaved = session()->getFlashdata('success') === 'Customer added.';
?>
<div class="page wrap">
    <header class="page-head">
        <div>
            <h1 class="page-title">Customer Accounts</h1>
            <p class="page-sub" data-default="<?= esc($summary, 'attr') ?>" id="customer-count"><?= esc($summary) ?></p>
        </div>
        <a class="btn" href="<?= site_url('customers/new') ?>"><?= icon('plus') ?> New customer</a>
    </header>

    <?php if ($count > 0): ?>
        <label class="search">
            <span class="sr-only">Search customers</span>
            <?= icon('search') ?>
            <input type="search" placeholder="Search customers" autocomplete="off"
                   data-search="#customer-list" data-empty="#customer-empty" data-count="#customer-count">
        </label>
    <?php endif; ?>

    <div class="list-card">
        <?php if ($count === 0): ?>
            <div class="empty">
                <p class="empty__title">No customers yet</p>
                <p>Add the first customer to start the list.</p>
                <a class="btn" href="<?= site_url('customers/new') ?>"><?= icon('plus') ?> New customer</a>
            </div>
        <?php else: ?>
            <table class="list" id="customer-list">
                <thead>
                    <tr>
                        <th class="c-av"><span class="sr-only">Avatar</span></th>
                        <th class="c-main">Name</th>
                        <th class="c-wide">ID</th>
                        <th class="c-wide">Email</th>
                        <th class="c-wide">Phone</th>
                        <th class="c-wide">Added</th>
                        <th class="c-act"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($customers as $customer): ?>
                    <?php
                        $search = strtolower($customer['full_name'] . ' ' . $customer['email'] . ' ' . ($customer['phone'] ?? ''));
                        $isNew  = $justSaved && (int) $customer['id'] === (int) $newestId;
                    ?>
                    <tr data-text="<?= esc($search, 'attr') ?>"<?= $isNew ? ' class="is-new"' : '' ?>>
                        <td class="c-av"><span class="initials" style="--h:<?= name_hue($customer['full_name']) ?>"><?= esc(initials($customer['full_name'])) ?></span></td>
                        <td class="c-main">
                            <div class="cell-name"><?= esc($customer['full_name']) ?></div>
                            <div class="cell-sub"><?= esc($customer['email']) ?></div>
                        </td>
                        <td class="c-wide cell-muted"><?= esc($customer['id']) ?></td>
                        <td class="c-wide"><?= esc($customer['email']) ?></td>
                        <td class="c-wide"><?= esc($customer['phone'] ?? '—') ?></td>
                        <td class="c-wide cell-muted"><?= esc(date('M j, Y', strtotime($customer['created_at']))) ?></td>
                        <td class="c-act">
                            <a class="btn btn--quiet btn--sm" href="<?= site_url('customers/' . $customer['id'] . '/edit') ?>" aria-label="Edit <?= esc($customer['full_name'], 'attr') ?>">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div class="empty" id="customer-empty" hidden>
                <p class="empty__title">No matches</p>
                <p>No customer matches “<span data-term></span>”. Try a name, email or phone number.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
