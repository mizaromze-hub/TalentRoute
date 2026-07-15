<?php
$states = ['Johor','Kedah','Kelantan','Melaka','Negeri Sembilan','Pahang','Perak','Perlis','Pulau Pinang','Sabah','Sarawak','Selangor','Terengganu','WP Kuala Lumpur','WP Labuan','WP Putrajaya'];
$industries = ['Information Technology','Software Development','Cyber Security','Networking','Data Analytics','Artificial Intelligence','Cloud Computing','Multimedia','Telecommunication','Finance','Healthcare','Manufacturing','Education','Government','Retail','Other'];
?>
<div class="max-w-5xl mx-auto py-8 space-y-8">
    <div class="flex items-center justify-between gap-4">
        <div>

    <span class="text-xs uppercase tracking-[3px] font-bold text-purple-600">
        COMPANY MANAGEMENT
    </span>

    <h1 class="text-4xl font-black mt-2 text-slate-900">
        Edit Company
    </h1>

    <p class="text-slate-500 mt-2">
        Update company information and contact details.
    </p>

</div>
        <?= $this->Html->link('← Company List', ['action' => 'index'], ['class' => 'font-bold text-purple-500']) ?>
    </div>

    <div class="rounded-3xl
            bg-gradient-to-br
            from-white
            to-slate-50
            dark:from-slate-800
            dark:to-slate-900
            p-8
            shadow-2xl
            border
            border-slate-200
            dark:border-slate-700">
        <?= $this->Form->create(null, ['class' => 'grid md:grid-cols-2 gap-5']) ?>
            <label class="block"><span class="font-bold">Company Name *</span><input name="company_name" value="<?= h($company['company_name'] ?? '') ?>" required maxlength="150" class="mt-2 w-full
rounded-xl
border
border-slate-300
bg-slate-50
focus:bg-white
focus:border-purple-500
focus:ring-4
focus:ring-purple-200
transition
px-4
py-3
dark:bg-slate-900
dark:border-slate-700"></label>
            <label class="block"><span class="font-bold">SSM Registration Number *</span><input name="registration_number" value="<?= h($company['registration_number'] ?? '') ?>" required inputmode="numeric" pattern="\d{12}" maxlength="12" class="mt-2 w-full
rounded-xl
border
border-slate-300
bg-slate-50
focus:bg-white
focus:border-purple-500
focus:ring-4
focus:ring-purple-200
transition
px-4
py-3
dark:bg-slate-900
dark:border-slate-700"></label>
            <label class="block"><span class="font-bold">Industry *</span><select name="industry" required class="mt-2 w-full
rounded-xl
border
border-slate-300
bg-slate-50
focus:bg-white
focus:border-purple-500
focus:ring-4
focus:ring-purple-200
transition
px-4
py-3
dark:bg-slate-900
dark:border-slate-700"><option value="">Select Industry</option><?php foreach ($industries as $industry): ?><option value="<?= h($industry) ?>" <?= ($company['industry'] ?? '') === $industry ? 'selected' : '' ?>><?= h($industry) ?></option><?php endforeach; ?></select></label>
            <label class="block"><span class="font-bold">Company Email *</span><input type="email" name="email" value="<?= h($company['email'] ?? '') ?>" required class="mt-2 w-full
rounded-xl
border
border-slate-300
bg-slate-50
focus:bg-white
focus:border-purple-500
focus:ring-4
focus:ring-purple-200
transition
px-4
py-3
dark:bg-slate-900
dark:border-slate-700"></label>
            <label class="block"><span class="font-bold">Company Representative *</span><input name="contact_person" value="<?= h($company['contact_person'] ?? '') ?>" required placeholder="Name of person in charge" class="mt-2 w-full
rounded-xl
border
border-slate-300
bg-slate-50
focus:bg-white
focus:border-purple-500
focus:ring-4
focus:ring-purple-200
transition
px-4
py-3
dark:bg-slate-900
dark:border-slate-700"><small class="text-slate-500">This is the person students or university staff should contact.</small></label>
            <label class="block"><span class="font-bold">Phone Number *</span><input name="phone_number" value="<?= h($company['phone_number'] ?? '') ?>" required inputmode="numeric" pattern="\d{10,11}" maxlength="11" class="mt-2 w-full
rounded-xl
border
border-slate-300
bg-slate-50
focus:bg-white
focus:border-purple-500
focus:ring-4
focus:ring-purple-200
transition
px-4
py-3
dark:bg-slate-900
dark:border-slate-700"></label>
            <label class="block"><span class="font-bold">Address Line 1 *</span><input name="address_line1" value="<?= h($company['address_line1'] ?? '') ?>" required class="mt-2 w-full
rounded-xl
border
border-slate-300
bg-slate-50
focus:bg-white
focus:border-purple-500
focus:ring-4
focus:ring-purple-200
transition
px-4
py-3
dark:bg-slate-900
dark:border-slate-700"></label>
            <label class="block"><span class="font-bold">Address Line 2</span><input name="address_line2" value="<?= h($company['address_line2'] ?? '') ?>" class="mt-2 w-full
rounded-xl
border
border-slate-300
bg-slate-50
focus:bg-white
focus:border-purple-500
focus:ring-4
focus:ring-purple-200
transition
px-4
py-3
dark:bg-slate-900
dark:border-slate-700"></label>
            <label class="block"><span class="font-bold">Postcode *</span><input name="postcode" value="<?= h($company['postcode'] ?? '') ?>" required inputmode="numeric" pattern="\d{5}" maxlength="5" class="mt-2 w-full
rounded-xl
border
border-slate-300
bg-slate-50
focus:bg-white
focus:border-purple-500
focus:ring-4
focus:ring-purple-200
transition
px-4
py-3
dark:bg-slate-900
dark:border-slate-700"></label>
            <label class="block"><span class="font-bold">City *</span><input name="city" value="<?= h($company['city'] ?? '') ?>" required class="mt-2 w-full
rounded-xl
border
border-slate-300
bg-slate-50
focus:bg-white
focus:border-purple-500
focus:ring-4
focus:ring-purple-200
transition
px-4
py-3
dark:bg-slate-900
dark:border-slate-700"></label>
            <label class="block"><span class="font-bold">State *</span><select name="state" required class="mt-2 w-full
rounded-xl
border
border-slate-300
bg-slate-50
focus:bg-white
focus:border-purple-500
focus:ring-4
focus:ring-purple-200
transition
px-4
py-3
dark:bg-slate-900
dark:border-slate-700"><option value="">Select State</option><?php foreach ($states as $state): ?><option value="<?= h($state) ?>" <?= ($company['state'] ?? '') === $state ? 'selected' : '' ?>><?= h($state) ?></option><?php endforeach; ?></select></label>
            <button
            type="submit"
            class="md:col-span-2
            mt-4
            rounded-xl
            bg-gradient-to-r
            from-purple-600
            to-indigo-600
            hover:from-purple-700
            hover:to-indigo-700
            text-white
            font-bold
            py-4
            shadow-xl
            transition">

            💾 Save Changes

            </button>
        <?= $this->Form->end() ?>
    </div>
</div>
