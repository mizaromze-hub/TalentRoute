<?php
$formData = $this->getRequest()->getData();

$states = [
    'Johor',
    'Kedah',
    'Kelantan',
    'Melaka',
    'Negeri Sembilan',
    'Pahang',
    'Perak',
    'Perlis',
    'Pulau Pinang',
    'Sabah',
    'Sarawak',
    'Selangor',
    'Terengganu',
    'WP Kuala Lumpur',
    'WP Labuan',
    'WP Putrajaya',
];

$industries = [
    'Information Technology',
    'Software Development',
    'Cyber Security',
    'Networking',
    'Data Analytics',
    'Artificial Intelligence',
    'Cloud Computing',
    'Multimedia',
    'Telecommunication',
    'Finance',
    'Healthcare',
    'Manufacturing',
    'Education',
    'Government',
    'Retail',
    'Other',
];
?>

<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black">Add New Company</h1>
            <p class="text-sm text-slate-500">
                Create the company login account and profile.
            </p>
        </div>

        <?= $this->Html->link(
            '← Company List',
            ['action' => 'index'],
            ['class' => 'font-bold text-purple-500']
        ) ?>
    </div>

    <div class="rounded-2xl bg-white dark:bg-slate-800 p-6 shadow">
        <?= $this->Form->create(null, [
            'class' => 'grid md:grid-cols-2 gap-5',
            'autocomplete' => 'off',
        ]) ?>

            <!-- Prevent Chrome / Google Password Manager autofill -->
            <input
                type="text"
                name="fake_company_username"
                autocomplete="username"
                tabindex="-1"
                aria-hidden="true"
                style="position:absolute;left:-9999px;width:1px;height:1px;opacity:0;"
            >

            <input
                type="password"
                name="fake_company_password"
                autocomplete="current-password"
                tabindex="-1"
                aria-hidden="true"
                style="position:absolute;left:-9999px;width:1px;height:1px;opacity:0;"
            >

            <label class="block">
                <span class="font-bold">Company Name *</span>

                <input
                    type="text"
                    name="company_name"
                    value="<?= h($formData['company_name'] ?? '') ?>"
                    required
                    maxlength="150"
                    autocomplete="organization"
                    placeholder="Example: Nexus Tech Sdn Bhd"
                    class="mt-2 w-full p-3 rounded-lg border bg-white dark:bg-slate-900"
                >
            </label>

            <label class="block">
                <span class="font-bold">SSM Registration Number *</span>

                <input
                    type="text"
                    name="registration_number"
                    value="<?= h($formData['registration_number'] ?? '') ?>"
                    required
                    inputmode="numeric"
                    pattern="[0-9]{12}"
                    minlength="12"
                    maxlength="12"
                    autocomplete="off"
                    placeholder="12 digits"
                    title="SSM registration number must contain exactly 12 digits."
                    class="mt-2 w-full p-3 rounded-lg border bg-white dark:bg-slate-900"
                >
            </label>

            <label class="block">
                <span class="font-bold">Industry *</span>

                <select
                    name="industry"
                    required
                    autocomplete="off"
                    class="mt-2 w-full p-3 rounded-lg border bg-white dark:bg-slate-900"
                >
                    <option value="">Select Industry</option>

                    <?php foreach ($industries as $industry): ?>
                        <option
                            value="<?= h($industry) ?>"
                            <?= ($formData['industry'] ?? '') === $industry
                                ? 'selected'
                                : '' ?>
                        >
                            <?= h($industry) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label class="block">
                <span class="font-bold">Company Email *</span>

                <input
                    type="email"
                    name="email"
                    value="<?= h($formData['email'] ?? '') ?>"
                    required
                    maxlength="150"
                    autocomplete="new-email"
                    autocapitalize="none"
                    spellcheck="false"
                    placeholder="company@example.com"
                    class="mt-2 w-full p-3 rounded-lg border bg-white dark:bg-slate-900"
                >
            </label>

            <label class="block">
                <span class="font-bold">Login Password *</span>

                <input
                    type="password"
                    name="password"
                    value=""
                    required
                    minlength="8"
                    maxlength="255"
                    autocomplete="new-password"
                    placeholder="Minimum 8 characters"
                    class="mt-2 w-full p-3 rounded-lg border bg-white dark:bg-slate-900"
                >

                <small class="text-slate-500">
                    Minimum 8 characters.
                </small>
            </label>

            <label class="block">
                <span class="font-bold">Company Representative *</span>

                <input
                    type="text"
                    name="contact_person"
                    value="<?= h($formData['contact_person'] ?? '') ?>"
                    required
                    maxlength="150"
                    autocomplete="name"
                    placeholder="Name of person in charge"
                    class="mt-2 w-full p-3 rounded-lg border bg-white dark:bg-slate-900"
                >

                <small class="text-slate-500">
                    Example: Nur Aina or Ahmad Faiz.
                </small>
            </label>

            <label class="block">
                <span class="font-bold">Phone Number *</span>

                <input
                    type="tel"
                    name="phone_number"
                    value="<?= h($formData['phone_number'] ?? '') ?>"
                    required
                    inputmode="numeric"
                    pattern="[0-9]{10,11}"
                    minlength="10"
                    maxlength="11"
                    autocomplete="tel"
                    placeholder="10 or 11 digits"
                    title="Phone number must contain 10 or 11 digits."
                    class="mt-2 w-full p-3 rounded-lg border bg-white dark:bg-slate-900"
                >
            </label>

            <label class="block">
                <span class="font-bold">Address Line 1 *</span>

                <input
                    type="text"
                    name="address_line1"
                    value="<?= h($formData['address_line1'] ?? '') ?>"
                    required
                    maxlength="255"
                    autocomplete="address-line1"
                    placeholder="Example: No. 1, Jalan Teknologi"
                    class="mt-2 w-full p-3 rounded-lg border bg-white dark:bg-slate-900"
                >
            </label>

            <label class="block">
                <span class="font-bold">Address Line 2</span>

                <input
                    type="text"
                    name="address_line2"
                    value="<?= h($formData['address_line2'] ?? '') ?>"
                    maxlength="255"
                    autocomplete="address-line2"
                    placeholder="Optional"
                    class="mt-2 w-full p-3 rounded-lg border bg-white dark:bg-slate-900"
                >
            </label>

            <label class="block">
                <span class="font-bold">Postcode *</span>

                <input
                    type="text"
                    name="postcode"
                    value="<?= h($formData['postcode'] ?? '') ?>"
                    required
                    inputmode="numeric"
                    pattern="[0-9]{5}"
                    minlength="5"
                    maxlength="5"
                    autocomplete="postal-code"
                    placeholder="Example: 40000"
                    title="Postcode must contain exactly 5 digits."
                    class="mt-2 w-full p-3 rounded-lg border bg-white dark:bg-slate-900"
                >
            </label>

            <label class="block">
                <span class="font-bold">City *</span>

                <input
                    type="text"
                    name="city"
                    value="<?= h($formData['city'] ?? '') ?>"
                    required
                    maxlength="100"
                    autocomplete="address-level2"
                    placeholder="Example: Shah Alam"
                    class="mt-2 w-full p-3 rounded-lg border bg-white dark:bg-slate-900"
                >
            </label>

            <label class="block">
                <span class="font-bold">State *</span>

                <select
                    name="state"
                    required
                    autocomplete="address-level1"
                    class="mt-2 w-full p-3 rounded-lg border bg-white dark:bg-slate-900"
                >
                    <option value="">Select State</option>

                    <?php foreach ($states as $state): ?>
                        <option
                            value="<?= h($state) ?>"
                            <?= ($formData['state'] ?? '') === $state
                                ? 'selected'
                                : '' ?>
                        >
                            <?= h($state) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>

            <button
                type="submit"
                class="md:col-span-2 rounded-lg bg-purple-600 hover:bg-purple-700 text-white font-bold p-3"
            >
                Create Company Account
            </button>

        <?= $this->Form->end() ?>
    </div>
</div>