<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<?php
    use App\Models\UserModel;

    $count   = count($users);
    $summary = $count === 1 ? '1 user can log in' : $count . ' users can log in';
    $newestId = $count ? max(array_column($users, 'id')) : null;
    $justCreated = session()->getFlashdata('success') === 'User added.';
?>
<div class="page wrap">
    <header class="page-head">
        <div>
            <h1 class="page-title">User Accounts</h1>
            <p class="page-sub" data-default="<?= esc($summary, 'attr') ?>" id="user-count"><?= esc($summary) ?></p>
        </div>
        <a class="btn" href="<?= site_url('users/new') ?>"><?= icon('plus') ?> New user</a>
    </header>

    <?php if ($count > 0): ?>
        <label class="search">
            <span class="sr-only">Search users</span>
            <?= icon('search') ?>
            <input type="search" placeholder="Search users" autocomplete="off"
                   data-search="#user-list" data-empty="#user-empty" data-count="#user-count">
        </label>
    <?php endif; ?>

    <div class="list-card">
        <?php if ($count === 0): ?>
            <div class="empty">
                <p class="empty__title">No users yet</p>
                <p>Add a user so someone can log in.</p>
                <a class="btn" href="<?= site_url('users/new') ?>"><?= icon('plus') ?> New user</a>
            </div>
        <?php else: ?>
            <table class="list" id="user-list">
                <thead>
                    <tr>
                        <th class="c-av">Avatar</th>
                        <th class="c-main">Name</th>
                        <th class="c-wide">ID</th>
                        <th class="c-wide">Username</th>
                        <th class="c-wide">Added</th>
                        <th class="c-act"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($users as $user): ?>
                    <?php
                        $search = strtolower($user['full_name'] . ' ' . $user['username']);
                        $isNew  = $justCreated && (int) $user['id'] === (int) $newestId;
                        $isMe   = (int) session()->get('user_id') === (int) $user['id'];
                    ?>
                    <tr data-text="<?= esc($search, 'attr') ?>"<?= $isNew ? ' class="is-new"' : '' ?>>
                        <td class="c-av"><img class="avatar" src="<?= esc(UserModel::avatarUrl($user['avatar'] ?? null)) ?>" alt="<?= esc($user['username'], 'attr') ?> avatar" loading="lazy"></td>
                        <td class="c-main">
                            <div class="cell-name"><span class="cell-name__text"><?= esc($user['full_name']) ?></span><?php if ($isMe): ?><span class="badge badge--live">You</span><?php endif; ?></div>
                            <div class="cell-sub">@<?= esc($user['username']) ?></div>
                        </td>
                        <td class="c-wide cell-muted"><?= esc($user['id']) ?></td>
                        <td class="c-wide">@<?= esc($user['username']) ?></td>
                        <td class="c-wide cell-muted"><?= esc(date('M j, Y', strtotime($user['created_at']))) ?></td>
                        <td class="c-act">
                            <a class="btn btn--quiet btn--sm" href="<?= site_url('users/' . $user['id'] . '/edit') ?>" aria-label="Edit <?= esc($user['username'], 'attr') ?>">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div class="empty" id="user-empty" hidden>
                <p class="empty__title">No matches</p>
                <p>No user matches “<span data-term></span>”. Try a name or username.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?= $this->endSection() ?>
