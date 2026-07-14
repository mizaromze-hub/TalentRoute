<?php
/**
 * @var \App\View\AppView $this
 * @var array $application
 * @var array $user
 */

$applyDate = !empty($application['apply_date'])
    ? (string)$application['apply_date']
    : '-';

$startDate = !empty($application['start_date'])
    ? (string)$application['start_date']
    : '';

$endDate = !empty($application['end_date'])
    ? (string)$application['end_date']
    : '';

$resume = $application['resume_file'] ?? null;
$today = date('Y-m-d');
?>

<div class="max-w-4xl mx-auto">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <p class="text-sm font-bold uppercase tracking-wide text-purple-600">
                Pending application
            </p>

            <h1 class="text-2xl font-black text-slate-900 dark:text-white">
                Edit Internship Application
            </h1>

            <p class="text-sm text-slate-500 dark:text-slate-400">
                You may edit this application until the company approves or rejects it.
            </p>
        </div>

        <a
            href="<?= $this->Url->build([
                'controller' => 'Applications',
                'action' => 'index',
            ]) ?>"
            class="inline-flex items-center justify-center rounded-lg border border-slate-300 px-4 py-2 font-semibold text-slate-700 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-800"
        >
            ← Back
        </a>
    </div>

    <div class="rounded-2xl bg-white p-5 shadow dark:bg-slate-800 md:p-7">
        <div class="mb-6 grid gap-3 sm:grid-cols-2">
            <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-900/60">
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Company
                </p>
                <p class="mt-1 font-bold text-slate-900 dark:text-white">
                    <?= h($application['display_company'] ?? 'Company') ?>
                </p>
            </div>

            <div class="rounded-xl bg-slate-50 p-4 dark:bg-slate-900/60">
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Application Date
                </p>
                <p class="mt-1 font-bold text-slate-900 dark:text-white">
                    <?= h($applyDate) ?>
                </p>
                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                    This date is automatic and cannot be edited.
                </p>
            </div>
        </div>

        <?= $this->Form->create(null, [
            'type' => 'file',
            'url' => [
                'controller' => 'Applications',
                'action' => 'edit',
                (int)$application['id'],
            ],
        ]) ?>

        <div class="grid gap-5 md:grid-cols-2">
            <div>
                <label for="start-date" class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-200">
                    Internship Start Date
                </label>

                <?= $this->Form->date('start_date', [
                    'id' => 'start-date',
                    'value' => $startDate,
                    'min' => $today,
                    'required' => true,
                    'class' => 'w-full rounded-lg border border-slate-300 bg-white p-3 text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-white',
                ]) ?>
            </div>

            <div>
                <label for="end-date" class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-200">
                    Internship End Date
                </label>

                <?= $this->Form->date('end_date', [
                    'id' => 'end-date',
                    'value' => $endDate,
                    'min' => $startDate !== '' ? $startDate : $today,
                    'required' => true,
                    'class' => 'w-full rounded-lg border border-slate-300 bg-white p-3 text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-white',
                ]) ?>
            </div>
        </div>

        <div class="mt-5">
            <label for="remarks" class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-200">
                Remarks
            </label>

            <?= $this->Form->textarea('remarks', [
                'id' => 'remarks',
                'value' => (string)($application['remarks'] ?? ''),
                'rows' => 5,
                'placeholder' => 'Write a short note for the company.',
                'class' => 'w-full rounded-lg border border-slate-300 bg-white p-3 text-slate-900 dark:border-slate-700 dark:bg-slate-900 dark:text-white',
            ]) ?>
        </div>

        <div class="mt-5">
            <label for="resume-file" class="mb-2 block text-sm font-bold text-slate-700 dark:text-slate-200">
                Replace Resume (PDF only, maximum 5 MB)
            </label>

            <?= $this->Form->file('resume_file', [
                'id' => 'resume-file',
                'accept' => 'application/pdf,.pdf',
                'class' => 'block w-full rounded-lg border border-slate-300 bg-white p-3 text-sm text-slate-700 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200',
            ]) ?>

            <?php if ($resume): ?>
                <a
                    class="mt-3 inline-flex items-center gap-2 font-semibold text-purple-600 hover:text-purple-700"
                    target="_blank"
                    rel="noopener noreferrer"
                    href="<?= $this->Url->build('/files/' . rawurlencode((string)$resume)) ?>"
                >
                    📄 View current resume
                </a>
            <?php endif; ?>
        </div>

        <div class="mt-7 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
            <a
                href="<?= $this->Url->build([
                    'controller' => 'Applications',
                    'action' => 'index',
                ]) ?>"
                class="inline-flex items-center justify-center rounded-lg border border-slate-300 px-5 py-3 font-bold text-slate-700 hover:bg-slate-100 dark:border-slate-700 dark:text-slate-200 dark:hover:bg-slate-900"
            >
                Cancel
            </a>

            <?= $this->Form->button('Save Changes', [
                'type' => 'submit',
                'class' => 'rounded-lg bg-purple-600 px-6 py-3 font-bold text-white hover:bg-purple-700',
            ]) ?>
        </div>

        <?= $this->Form->end() ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const startDate = document.getElementById('start-date');
    const endDate = document.getElementById('end-date');

    if (!startDate || !endDate) {
        return;
    }

    function syncEndDateMinimum() {
        endDate.min = startDate.value || '<?= h($today) ?>';

        if (endDate.value && startDate.value && endDate.value < startDate.value) {
            endDate.value = startDate.value;
        }
    }

    startDate.addEventListener('change', syncEndDateMinimum);
    syncEndDateMinimum();
});
</script>
