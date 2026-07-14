<?php
/**
 * @var array $company
 * @var array $user
 * @var string $role
 */
?>

<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between mb-6">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-purple-500 mb-2">
                <?= $role === 'company' ? 'Company / Profile' : 'Admin / Companies' ?>
            </p>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white">Company Profile</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">
                View company and representative information.
            </p>
        </div>

        <div class="flex flex-wrap gap-3">
            <?php if ($role === 'admin'): ?>
                <?= $this->Html->link(
                    '← Company List',
                    ['action' => 'index'],
                    ['class' => 'px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 font-bold']
                ) ?>
            <?php endif; ?>

            <?= $this->Html->link(
                '✏️ Edit Profile',
                $role === 'company'
                    ? ['action' => 'edit']
                    : ['action' => 'edit', $company['id']],
                ['class' => 'px-5 py-3 rounded-xl bg-purple-600 hover:bg-purple-700 text-white font-bold']
            ) ?>
        </div>
    </div>

    <div class="bg-slate-100 dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="p-6 md:p-8 border-b border-slate-200 dark:border-slate-700">
            <div class="flex items-start gap-4">
                <div class="w-16 h-16 rounded-2xl bg-purple-600 text-white flex items-center justify-center text-3xl">🏢</div>
                <div>
                    <h2 class="text-2xl font-black text-slate-900 dark:text-white">
                        <?= h((string)$company['company_name']) ?>
                    </h2>
                    <p class="text-slate-500 dark:text-slate-400 mt-1">
                        <?= h((string)($company['industry'] ?? 'Other')) ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 p-6 md:p-8">
            <?php
            $details = [
                'SSM Registration Number' => $company['registration_number'] ?? '-',
                'Company Email' => $company['email'] ?? '-',
                'Company Representative' => $company['contact_person'] ?? '-',
                'Phone Number' => $company['phone_number'] ?? '-',
                'Address Line 1' => $company['address_line1'] ?? '-',
                'Address Line 2' => $company['address_line2'] ?? '-',
                'Postcode' => $company['postcode'] ?? '-',
                'City' => $company['city'] ?? '-',
                'State' => $company['state'] ?? '-',
            ];
            ?>

            <?php foreach ($details as $label => $value): ?>
                <div class="rounded-xl bg-white dark:bg-slate-900 p-4 border border-slate-200 dark:border-slate-700">
                    <p class="text-xs uppercase tracking-wide text-slate-500 dark:text-slate-400 mb-1">
                        <?= h($label) ?>
                    </p>
                    <p class="font-bold text-slate-900 dark:text-white break-words">
                        <?= h(trim((string)$value) !== '' ? (string)$value : '-') ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
