<?php
/**
 * @var array $company
 * @var array $user
 * @var string $role
 */
?>

<div class="max-w-6xl mx-auto px-4 py-8 space-y-6">

    <!-- HEADER -->
    <div class="rounded-3xl bg-gradient-to-r from-purple-600 to-indigo-600 text-white p-8 shadow-xl">

        <div class="flex justify-between items-center flex-wrap gap-4">

            <div class="flex items-center gap-5">

                <div class="w-20 h-20 rounded-3xl bg-white/20 flex items-center justify-center text-5xl">
                    🏢
                </div>

                <div>

                    <p class="uppercase tracking-[3px] text-sm opacity-80">
                        Company Profile
                    </p>

                    <h1 class="text-4xl font-black mt-1">
                        <?= h($company['company_name']) ?>
                    </h1>

                    <p class="mt-2 opacity-90">
                        <?= h($company['industry'] ?: 'Other Industry') ?>
                    </p>

                </div>

            </div>

            <?= $this->Html->link(
                '✏️ Edit Profile',
                $role === 'company'
                    ? ['action'=>'edit']
                    : ['action'=>'edit',$company['id']],
                [
                    'class'=>'bg-white text-purple-700 font-bold px-6 py-3 rounded-xl hover:bg-purple-50 transition shadow-lg'
                ]
            ) ?>

        </div>

    </div>


    <!-- COMPANY INFO -->
    <div class="bg-white rounded-3xl shadow-lg border border-slate-200">

        <div class="p-6 border-b">

            <h2 class="text-xl font-black text-slate-800">
                🏢 Company Information
            </h2>

        </div>

        <div class="grid md:grid-cols-2 gap-5 p-6">

            <div class="bg-slate-50 rounded-2xl p-5">
                <p class="text-xs uppercase text-slate-500">SSM Registration</p>
                <p class="text-lg font-bold mt-2"><?= h($company['registration_number']) ?></p>
            </div>

            <div class="bg-slate-50 rounded-2xl p-5">
                <p class="text-xs uppercase text-slate-500">Industry</p>
                <p class="text-lg font-bold mt-2"><?= h($company['industry'] ?: '-') ?></p>
            </div>

        </div>

    </div>


    <!-- CONTACT -->
    <div class="bg-white rounded-3xl shadow-lg border border-slate-200">

        <div class="p-6 border-b">

            <h2 class="text-xl font-black text-slate-800">
                📞 Contact Information
            </h2>

        </div>

        <div class="grid md:grid-cols-2 gap-5 p-6">

            <div class="bg-slate-50 rounded-2xl p-5">
                <p class="text-xs uppercase text-slate-500">Representative</p>
                <p class="text-lg font-bold mt-2"><?= h($company['contact_person'] ?: '-') ?></p>
            </div>

            <div class="bg-slate-50 rounded-2xl p-5">
                <p class="text-xs uppercase text-slate-500">Phone Number</p>
                <p class="text-lg font-bold mt-2"><?= h($company['phone_number'] ?: '-') ?></p>
            </div>

            <div class="bg-slate-50 rounded-2xl p-5 md:col-span-2">
                <p class="text-xs uppercase text-slate-500">Email Address</p>
                <p class="text-lg font-bold mt-2"><?= h($company['email']) ?></p>
            </div>

        </div>

    </div>


    <!-- ADDRESS -->
    <div class="bg-white rounded-3xl shadow-lg border border-slate-200">

        <div class="p-6 border-b">

            <h2 class="text-xl font-black text-slate-800">
                📍 Company Address
            </h2>

        </div>

        <div class="p-6">

            <div class="bg-slate-50 rounded-2xl p-6">

                <p><?= h($company['address_line1']) ?></p>

                <?php if (!empty($company['address_line2'])): ?>
                    <p><?= h($company['address_line2']) ?></p>
                <?php endif; ?>

                <p class="mt-2">
                    <?= h($company['postcode']) ?>
                    <?= h($company['city']) ?>,
                    <?= h($company['state']) ?>
                </p>

            </div>

        </div>

    </div>

</div>