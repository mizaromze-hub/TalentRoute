<?php
$states = ['Johor','Kedah','Kelantan','Melaka','Negeri Sembilan','Pahang','Perak','Perlis','Pulau Pinang','Sabah','Sarawak','Selangor','Terengganu','WP Kuala Lumpur','WP Labuan','WP Putrajaya'];
$industries = ['Information Technology','Software Development','Cyber Security','Networking','Data Analytics','Artificial Intelligence','Cloud Computing','Multimedia','Telecommunication','Finance','Healthcare','Manufacturing','Education','Government','Retail','Other'];
?>
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between gap-4">
        <div><h1 class="text-2xl font-black">Edit Company</h1><p class="text-sm text-slate-500">Update company and representative information.</p></div>
        <?= $this->Html->link('← Company List', ['action' => 'index'], ['class' => 'font-bold text-purple-500']) ?>
    </div>

    <div class="rounded-2xl bg-white dark:bg-slate-800 p-6 shadow">
        <?= $this->Form->create(null, ['class' => 'grid md:grid-cols-2 gap-5']) ?>
            <label class="block"><span class="font-bold">Company Name *</span><input name="company_name" value="<?= h($company['company_name'] ?? '') ?>" required maxlength="150" class="mt-2 w-full p-3 rounded-lg border bg-white dark:bg-slate-900"></label>
            <label class="block"><span class="font-bold">SSM Registration Number *</span><input name="registration_number" value="<?= h($company['registration_number'] ?? '') ?>" required inputmode="numeric" pattern="\d{12}" maxlength="12" class="mt-2 w-full p-3 rounded-lg border bg-white dark:bg-slate-900"></label>
            <label class="block"><span class="font-bold">Industry *</span><select name="industry" required class="mt-2 w-full p-3 rounded-lg border bg-white dark:bg-slate-900"><option value="">Select Industry</option><?php foreach ($industries as $industry): ?><option value="<?= h($industry) ?>" <?= ($company['industry'] ?? '') === $industry ? 'selected' : '' ?>><?= h($industry) ?></option><?php endforeach; ?></select></label>
            <label class="block"><span class="font-bold">Company Email *</span><input type="email" name="email" value="<?= h($company['email'] ?? '') ?>" required class="mt-2 w-full p-3 rounded-lg border bg-white dark:bg-slate-900"></label>
            <label class="block"><span class="font-bold">Company Representative *</span><input name="contact_person" value="<?= h($company['contact_person'] ?? '') ?>" required placeholder="Name of person in charge" class="mt-2 w-full p-3 rounded-lg border bg-white dark:bg-slate-900"><small class="text-slate-500">This is the person students or university staff should contact.</small></label>
            <label class="block"><span class="font-bold">Phone Number *</span><input name="phone_number" value="<?= h($company['phone_number'] ?? '') ?>" required inputmode="numeric" pattern="\d{10,11}" maxlength="11" class="mt-2 w-full p-3 rounded-lg border bg-white dark:bg-slate-900"></label>
            <label class="block"><span class="font-bold">Address Line 1 *</span><input name="address_line1" value="<?= h($company['address_line1'] ?? '') ?>" required class="mt-2 w-full p-3 rounded-lg border bg-white dark:bg-slate-900"></label>
            <label class="block"><span class="font-bold">Address Line 2</span><input name="address_line2" value="<?= h($company['address_line2'] ?? '') ?>" class="mt-2 w-full p-3 rounded-lg border bg-white dark:bg-slate-900"></label>
            <label class="block"><span class="font-bold">Postcode *</span><input name="postcode" value="<?= h($company['postcode'] ?? '') ?>" required inputmode="numeric" pattern="\d{5}" maxlength="5" class="mt-2 w-full p-3 rounded-lg border bg-white dark:bg-slate-900"></label>
            <label class="block"><span class="font-bold">City *</span><input name="city" value="<?= h($company['city'] ?? '') ?>" required class="mt-2 w-full p-3 rounded-lg border bg-white dark:bg-slate-900"></label>
            <label class="block"><span class="font-bold">State *</span><select name="state" required class="mt-2 w-full p-3 rounded-lg border bg-white dark:bg-slate-900"><option value="">Select State</option><?php foreach ($states as $state): ?><option value="<?= h($state) ?>" <?= ($company['state'] ?? '') === $state ? 'selected' : '' ?>><?= h($state) ?></option><?php endforeach; ?></select></label>
            <button type="submit" class="md:col-span-2 rounded-lg bg-purple-600 hover:bg-purple-700 text-white font-bold p-3">Save Company</button>
        <?= $this->Form->end() ?>
    </div>
</div>
