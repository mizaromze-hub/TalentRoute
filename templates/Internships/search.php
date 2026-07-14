<?php
/**
 * templates/Internships/search.php
 *
 * IMPORTANT: This file contains page content only.
 * Do not put <!DOCTYPE>, <html>, <body>, <nav>, <main>, or <footer> here.
 */
?>

<div class="space-y-6">
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-purple-500">
                Student / Internships
            </p>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white mt-1">
                Browse Internships
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                Select a company, complete the application form and submit your application.
            </p>
        </div>

        <form method="get"
              action="<?= $this->Url->build(['controller' => 'Internships', 'action' => 'search']) ?>"
              class="flex w-full lg:w-auto">
            <input type="search"
                   name="keyword"
                   value="<?= h($keyword ?? '') ?>"
                   placeholder="Search company, industry or location"
                   class="min-w-0 lg:w-80 flex-1 p-3 rounded-l-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:outline-none focus:border-purple-500">
            <button type="submit"
                    class="bg-purple-600 hover:bg-purple-700 text-white font-bold px-5 rounded-r-xl">
                Search
            </button>
        </form>
    </div>

    <div class="rounded-2xl bg-gradient-to-r from-purple-700 to-indigo-700 text-white p-5 shadow">
        <p class="text-xs font-bold uppercase tracking-wider opacity-75">
            Application Process
        </p>
        <p class="font-black mt-1">
            Select Company → Complete Form → Submit Application
        </p>
        <p class="text-sm opacity-80 mt-1">
            Proposed dates, an application message and a PDF resume are required.
        </p>
    </div>

    <?php if (empty($companies)): ?>
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-12 shadow text-center">
            <div class="text-5xl">🏢</div>
            <h2 class="mt-4 font-black text-lg text-slate-900 dark:text-white">
                No Companies Found
            </h2>
            <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">
                Try another company name, industry or location.
            </p>
        </div>
    <?php endif; ?>

    <div class="grid md:grid-cols-2 xl:grid-cols-3 gap-5">
        <?php foreach ($companies as $company): ?>
            <?php
            $companyId = (int)$company['id'];
            $applicationStatus = $appliedCompanies[$companyId] ?? null;
            $hasApplied = $applicationStatus !== null;
            $addressParts = array_filter([
                trim((string)($company['address_line1'] ?? '')),
                trim((string)($company['address_line2'] ?? '')),
                trim((string)(($company['postcode'] ?? '') . ' ' . ($company['city'] ?? ''))),
                trim((string)($company['state'] ?? '')),
            ]);
            ?>
            <article class="bg-white dark:bg-slate-800 rounded-2xl shadow overflow-hidden flex flex-col">
                <div class="h-2 bg-gradient-to-r from-purple-600 to-indigo-600"></div>
                <div class="p-5 flex-1 flex flex-col">
                    <div class="flex items-start justify-between gap-3">
                        <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300 flex items-center justify-center text-xl font-black">
                            <?= h(strtoupper(substr((string)$company['company_name'], 0, 1))) ?>
                        </div>

                        <?php if ($hasApplied): ?>
                            <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-200">
                                <?= h(ucfirst((string)$applicationStatus)) ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <h2 class="mt-4 text-lg font-black text-slate-900 dark:text-white">
                        <?= h($company['company_name']) ?>
                    </h2>

                    <p class="mt-1 text-xs font-semibold text-purple-600 dark:text-purple-400">
                        <?= h($company['industry'] ?: 'Industry not specified') ?>
                    </p>

                    <div class="mt-4 text-sm text-slate-600 dark:text-slate-300 leading-relaxed flex-1">
                        <?php if (empty($addressParts)): ?>
                            <p>Company address not provided.</p>
                        <?php else: ?>
                            <?php foreach ($addressParts as $line): ?>
                                <p><?= h($line) ?></p>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>

                    <?php if ($hasApplied): ?>
                        <a href="<?= $this->Url->build(['controller' => 'Applications', 'action' => 'index']) ?>"
                           class="mt-5 w-full inline-flex justify-center bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-700 dark:text-slate-200 font-bold p-3 rounded-xl">
                            View Application
                        </a>
                    <?php else: ?>
                        <a href="<?= $this->Url->build(['controller' => 'Internships', 'action' => 'add', $companyId]) ?>"
                           class="mt-5 w-full inline-flex justify-center bg-purple-600 hover:bg-purple-700 text-white font-bold p-3 rounded-xl transition">
                            View &amp; Apply
                        </a>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
</div>
