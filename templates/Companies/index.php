<?php
/** @var array $companies */
/** @var string $keyword */
/** @var string $role */
$isAdmin = $role === 'admin';
?>
<div class="space-y-6">
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">Company List</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Internship company profiles.</p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3">
            <?= $this->Form->create(null, ['type' => 'get', 'class' => 'flex gap-2']) ?>
                <?= $this->Form->control('keyword', [
                    'label' => false,
                    'value' => $keyword,
                    'placeholder' => 'Search company, SSM, industry or state',
                    'class' => 'w-80 max-w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2'
                ]) ?>
                <?= $this->Form->button('Search', ['class' => 'rounded-lg bg-slate-700 text-white font-bold px-4 py-2']) ?>
            <?= $this->Form->end() ?>

            <?php if ($isAdmin): ?>
                <?= $this->Html->link('+ Add Company', ['controller' => 'Companies', 'action' => 'add'], [
                    'class' => 'inline-flex items-center justify-center rounded-lg bg-purple-600 hover:bg-purple-700 text-white font-bold px-5 py-2'
                ]) ?>
            <?php endif; ?>
        </div>
    </div>

    <?php if (!$companies): ?>
        <div class="rounded-2xl bg-white dark:bg-slate-800 p-8 text-center text-slate-500">No companies found.</div>
    <?php else: ?>
        <div class="grid md:grid-cols-2 gap-5">
            <?php foreach ($companies as $company): ?>
                <article class="rounded-2xl bg-white dark:bg-slate-800 p-6 shadow">
                    <div class="flex items-start justify-between gap-3">
                        <h2 class="text-xl font-black text-slate-900 dark:text-white"><?= h($company['company_name']) ?></h2>
                        <span class="rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 px-3 py-1 text-xs font-bold"><?= h($company['industry'] ?: 'Other') ?></span>
                    </div>
                    <div class="mt-4 space-y-1 text-sm text-slate-600 dark:text-slate-300">
                        <p><strong>SSM:</strong> <?= h($company['registration_number']) ?></p>
                        <p><strong>Company Representative:</strong> <?= h($company['contact_person'] ?: '-') ?></p>
                        <p><strong>Phone:</strong> <?= h($company['phone_number'] ?: '-') ?></p>
                        <p><strong>Email:</strong> <?= h($company['email']) ?></p>
                        <p class="pt-2"><?= h($company['address_line1']) ?></p>
                        <?php if (!empty($company['address_line2'])): ?><p><?= h($company['address_line2']) ?></p><?php endif; ?>
                        <p><?= h(trim(($company['postcode'] ?? '') . ' ' . ($company['city'] ?? ''))) ?>, <?= h($company['state']) ?></p>
                    </div>
                    <div class="mt-5">
                        <?= $this->Html->link('Edit Profile', ['action' => 'edit', $company['id']], ['class' => 'font-bold text-purple-600 hover:text-purple-700']) ?>
                    </div>
                </article>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
