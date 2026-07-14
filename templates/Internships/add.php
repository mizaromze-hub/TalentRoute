<?php
/**
 * @var \App\View\AppView $this
 * @var array $user
 * @var array $student
 * @var array $company
 */

$formData = $this->getRequest()->getData();

$startDate = (string)(
    $formData['start_date'] ?? ''
);

$endDate = (string)(
    $formData['end_date'] ?? ''
);

$remarks = (string)(
    $formData['remarks'] ?? ''
);

$existingResume =
    $student['resume_path']
    ?: ($student['resume'] ?? null);

$companyAddress = array_filter([
    trim(
        (string)(
            $company['address_line1'] ?? ''
        )
    ),

    trim(
        (string)(
            $company['address_line2'] ?? ''
        )
    ),

    trim(
        (string)(
            ($company['postcode'] ?? '')
            . ' '
            . ($company['city'] ?? '')
        )
    ),

    trim(
        (string)($company['state'] ?? '')
    ),
]);
?>

<div class="space-y-6">

    <div
        class="flex flex-col sm:flex-row
        sm:items-center sm:justify-between gap-4"
    >
        <div>
            <p
                class="text-xs font-bold uppercase
                tracking-widest text-purple-500"
            >
                Student / Internships / Application
            </p>

            <h1
                class="text-2xl font-black
                text-slate-900 dark:text-white mt-1"
            >
                Internship Application
            </h1>

            <p
                class="text-sm text-slate-500
                dark:text-slate-400 mt-1"
            >
                Complete the form before submitting
                your application.
            </p>
        </div>

        <a
            href="<?= $this->Url->build([
                'controller' => 'Internships',
                'action' => 'search',
            ]) ?>"
            class="inline-flex justify-center
            border border-slate-300
            dark:border-slate-700
            text-slate-700 dark:text-slate-200
            hover:bg-slate-100
            dark:hover:bg-slate-800
            font-bold px-4 py-2 rounded-xl"
        >
            ← Back to Internships
        </a>
    </div>

    <div
        class="grid xl:grid-cols-4
        gap-6 items-start"
    >
        <aside class="space-y-5">

            <div
                class="bg-white dark:bg-slate-800
                rounded-2xl p-5 shadow"
            >
                <div
                    class="w-14 h-14 rounded-xl
                    bg-purple-100 text-purple-700
                    dark:bg-purple-900/30
                    dark:text-purple-300
                    flex items-center justify-center
                    text-2xl font-black"
                >
                    <?= h(
                        strtoupper(
                            substr(
                                (string)$company[
                                    'company_name'
                                ],
                                0,
                                1
                            )
                        )
                    ) ?>
                </div>

                <h2
                    class="mt-4 text-xl font-black
                    text-slate-900 dark:text-white"
                >
                    <?= h($company['company_name']) ?>
                </h2>

                <p
                    class="mt-1 text-xs font-semibold
                    text-purple-600
                    dark:text-purple-400"
                >
                    Registration: <?= h($company['registration_number'] ?? '-') ?>
                </p>

                <div
                    class="mt-4 text-sm
                    text-slate-600
                    dark:text-slate-300"
                >
                    <?php foreach (
                        $companyAddress as $line
                    ): ?>
                        <p><?= h($line) ?></p>
                    <?php endforeach; ?>
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-800
                rounded-2xl p-5 shadow"
            >
                <h2
                    class="font-black text-slate-900
                    dark:text-white"
                >
                    Student Information
                </h2>

                <div
                    class="mt-4 space-y-3 text-sm
                    text-slate-600
                    dark:text-slate-300"
                >
                    <div>
                        <p class="text-xs text-slate-500">
                            Name
                        </p>

                        <p class="font-bold">
                            <?= h($student['name']) ?>
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-500">
                            Matrix Number
                        </p>

                        <p class="font-bold">
                            <?= h(
                                $student['matrix_number']
                            ) ?>
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-500">
                            Programme
                        </p>

                        <p class="font-bold">
                            <?= h($student['course']) ?>
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-500">
                            Semester
                        </p>

                        <p class="font-bold">
                            <?= h(
                                (string)$student['semester']
                            ) ?>
                        </p>
                    </div>
                </div>
            </div>
        </aside>

        <div
            class="xl:col-span-3
            bg-white dark:bg-slate-800
            rounded-2xl shadow overflow-hidden"
        >
            <?= $this->Form->create(null, [
                'type' => 'file',
                'id' => 'internshipApplicationForm',
            ]) ?>

            <div class="p-5 md:p-7 space-y-7">

                <section>
                    <div
                        class="flex items-center gap-3
                        border-b border-slate-200
                        dark:border-slate-700
                        pb-3 mb-5"
                    >
                        <span
                            class="w-9 h-9 rounded-full
                            bg-purple-100 text-purple-700
                            dark:bg-purple-900/30
                            dark:text-purple-300
                            flex items-center justify-center
                            font-black"
                        >
                            1
                        </span>

                        <div>
                            <h2
                                class="font-black
                                text-slate-900
                                dark:text-white"
                            >
                                Proposed Internship Period
                            </h2>

                            <p
                                class="text-xs text-slate-500
                                dark:text-slate-400"
                            >
                                Company may confirm or amend
                                these dates during approval.
                            </p>
                        </div>
                    </div>

                    <div
                        class="grid md:grid-cols-2 gap-5"
                    >
                        <div>
                            <label
                                for="start_date"
                                class="block text-sm font-bold
                                text-slate-700
                                dark:text-slate-200 mb-2"
                            >
                                Proposed Start Date *
                            </label>

                            <input
                                id="start_date"
                                type="date"
                                name="start_date"
                                value="<?= h($startDate) ?>"
                                min="<?= h(date('Y-m-d')) ?>"
                                required
                                class="w-full p-3 rounded-xl
                                bg-slate-50 dark:bg-slate-900
                                border border-slate-200
                                dark:border-slate-700
                                text-slate-900 dark:text-white"
                            >
                        </div>

                        <div>
                            <label
                                for="end_date"
                                class="block text-sm font-bold
                                text-slate-700
                                dark:text-slate-200 mb-2"
                            >
                                Proposed End Date *
                            </label>

                            <input
                                id="end_date"
                                type="date"
                                name="end_date"
                                value="<?= h($endDate) ?>"
                                min="<?= h(date('Y-m-d')) ?>"
                                required
                                class="w-full p-3 rounded-xl
                                bg-slate-50 dark:bg-slate-900
                                border border-slate-200
                                dark:border-slate-700
                                text-slate-900 dark:text-white"
                            >
                        </div>
                    </div>

                    <div
                        class="mt-4 rounded-xl
                        bg-purple-50
                        dark:bg-purple-900/20
                        border border-purple-200
                        dark:border-purple-800
                        p-4"
                    >
                        <p
                            class="text-xs text-purple-600
                            dark:text-purple-400"
                        >
                            Estimated Duration
                        </p>

                        <p
                            id="durationDisplay"
                            class="font-black text-purple-700
                            dark:text-purple-300 mt-1"
                        >
                            Select internship dates
                        </p>
                    </div>
                </section>

                <section>
                    <div
                        class="flex items-center gap-3
                        border-b border-slate-200
                        dark:border-slate-700
                        pb-3 mb-5"
                    >
                        <span
                            class="w-9 h-9 rounded-full
                            bg-blue-100 text-blue-700
                            dark:bg-blue-900/30
                            dark:text-blue-300
                            flex items-center justify-center
                            font-black"
                        >
                            2
                        </span>

                        <div>
                            <h2
                                class="font-black
                                text-slate-900
                                dark:text-white"
                            >
                                Application Message
                            </h2>

                            <p
                                class="text-xs text-slate-500
                                dark:text-slate-400"
                            >
                                Briefly explain your interest
                                and suitability.
                            </p>
                        </div>
                    </div>

                    <label
                        for="remarks"
                        class="block text-sm font-bold
                        text-slate-700
                        dark:text-slate-200 mb-2"
                    >
                        Cover Letter / Application Message *
                    </label>

                    <textarea
                        id="remarks"
                        name="remarks"
                        rows="7"
                        required
                        minlength="20"
                        maxlength="2000"
                        placeholder="Introduce yourself, explain why you are interested in this company and describe relevant skills..."
                        class="w-full p-3 rounded-xl
                        bg-slate-50 dark:bg-slate-900
                        border border-slate-200
                        dark:border-slate-700
                        text-slate-900 dark:text-white"
                    ><?= h($remarks) ?></textarea>

                    <div
                        class="mt-1 flex justify-between
                        text-xs text-slate-500"
                    >
                        <span>
                            Minimum 20 characters
                        </span>

                        <span id="remarksCounter">
                            0 / 2000
                        </span>
                    </div>
                </section>

                <section>
                    <div
                        class="flex items-center gap-3
                        border-b border-slate-200
                        dark:border-slate-700
                        pb-3 mb-5"
                    >
                        <span
                            class="w-9 h-9 rounded-full
                            bg-emerald-100
                            text-emerald-700
                            dark:bg-emerald-900/30
                            dark:text-emerald-300
                            flex items-center justify-center
                            font-black"
                        >
                            3
                        </span>

                        <div>
                            <h2
                                class="font-black
                                text-slate-900
                                dark:text-white"
                            >
                                Resume
                            </h2>

                            <p
                                class="text-xs text-slate-500
                                dark:text-slate-400"
                            >
                                Use your existing resume or
                                upload a new PDF.
                            </p>
                        </div>
                    </div>

                    <?php if ($existingResume): ?>
                        <div
                            class="mb-4 rounded-xl
                            border border-emerald-200
                            bg-emerald-50
                            dark:border-emerald-800
                            dark:bg-emerald-900/20
                            p-4 flex flex-col sm:flex-row
                            sm:items-center
                            sm:justify-between gap-3"
                        >
                            <div>
                                <p
                                    class="font-bold
                                    text-emerald-700
                                    dark:text-emerald-300"
                                >
                                    ✓ Existing resume available
                                </p>

                                <p
                                    class="text-xs text-emerald-600
                                    dark:text-emerald-400 mt-1"
                                >
                                    Leave the upload field empty
                                    to use this resume.
                                </p>
                            </div>

                            <a
                                href="<?= $this->Url->build(
                                    '/files/'
                                    . rawurlencode(
                                        (string)$existingResume
                                    )
                                ) ?>"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="text-sm font-bold
                                text-emerald-700
                                dark:text-emerald-300"
                            >
                                View Resume
                            </a>
                        </div>
                    <?php endif; ?>

                    <label
                        for="resume_file"
                        class="block rounded-2xl
                        border-2 border-dashed
                        border-slate-300
                        dark:border-slate-700
                        p-7 text-center cursor-pointer
                        hover:border-purple-500
                        hover:bg-purple-50
                        dark:hover:bg-purple-900/10"
                    >
                        <div class="text-4xl">
                            📄
                        </div>

                        <p
                            class="mt-3 font-black
                            text-slate-900 dark:text-white"
                        >
                            Upload New Resume
                        </p>

                        <p
                            id="resumeFileName"
                            class="mt-1 text-sm text-slate-500
                            dark:text-slate-400"
                        >
                            PDF only · Maximum 5 MB
                        </p>

                        <input
                            id="resume_file"
                            type="file"
                            name="resume_file"
                            accept=".pdf,application/pdf"
                            class="hidden"
                            <?= !$existingResume
                                ? 'required'
                                : ''
                            ?>
                        >
                    </label>
                </section>

            </div>

            <div
                class="px-5 md:px-7 py-5
                bg-slate-50 dark:bg-slate-900/50
                border-t border-slate-200
                dark:border-slate-700
                flex flex-col-reverse sm:flex-row
                sm:items-center
                sm:justify-between gap-3"
            >
                <p
                    class="text-xs text-slate-500
                    dark:text-slate-400"
                >
                    Application date:
                    <?= h(date('d F Y')) ?>
                </p>

                <div class="flex gap-3">
                    <a
                        href="<?= $this->Url->build([
                            'controller' => 'Internships',
                            'action' => 'search',
                        ]) ?>"
                        class="inline-flex justify-center
                        border border-slate-300
                        dark:border-slate-700
                        text-slate-700
                        dark:text-slate-200
                        px-5 py-3 rounded-xl font-bold"
                    >
                        Cancel
                    </a>

                    <button
                        id="submitApplicationButton"
                        type="submit"
                        class="inline-flex justify-center
                        bg-purple-600
                        hover:bg-purple-700
                        text-white px-6 py-3
                        rounded-xl font-bold shadow"
                    >
                        Submit Application
                    </button>
                </div>
            </div>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const startDate =
        document.getElementById('start_date');

    const endDate =
        document.getElementById('end_date');

    const durationDisplay =
        document.getElementById(
            'durationDisplay'
        );

    const remarks =
        document.getElementById('remarks');

    const remarksCounter =
        document.getElementById(
            'remarksCounter'
        );

    const resumeInput =
        document.getElementById(
            'resume_file'
        );

    const resumeFileName =
        document.getElementById(
            'resumeFileName'
        );

    const form =
        document.getElementById(
            'internshipApplicationForm'
        );

    const submitButton =
        document.getElementById(
            'submitApplicationButton'
        );

    function updateDuration() {
        if (
            !startDate.value
            || !endDate.value
        ) {
            durationDisplay.textContent =
                'Select internship dates';

            return;
        }

        endDate.min = startDate.value;

        const start = new Date(
            startDate.value + 'T00:00:00'
        );

        const end = new Date(
            endDate.value + 'T00:00:00'
        );

        const days = Math.floor(
            (
                end.getTime()
                - start.getTime()
            ) / 86400000
        ) + 1;

        if (days <= 1) {
            durationDisplay.textContent =
                'End date must be after start date';

            durationDisplay.className =
                'font-black text-red-600 mt-1';

            return;
        }

        const weeks =
            Math.floor(days / 7);

        const remainingDays =
            days % 7;

        let durationText =
            days + ' days';

        if (weeks > 0) {
            durationText +=
                ' · ' + weeks + ' week(s)';

            if (remainingDays > 0) {
                durationText +=
                    ' and '
                    + remainingDays
                    + ' day(s)';
            }
        }

        durationDisplay.textContent =
            durationText;

        durationDisplay.className =
            'font-black text-purple-700 '
            + 'dark:text-purple-300 mt-1';
    }

    startDate.addEventListener(
        'change',
        updateDuration
    );

    endDate.addEventListener(
        'change',
        updateDuration
    );

    remarks.addEventListener(
        'input',
        function () {
            remarksCounter.textContent =
                remarks.value.length
                + ' / 2000';
        }
    );

    resumeInput.addEventListener(
        'change',
        function () {
            const file =
                resumeInput.files[0];

            if (!file) {
                resumeFileName.textContent =
                    'PDF only · Maximum 5 MB';

                return;
            }

            const extension = file.name
                .split('.')
                .pop()
                .toLowerCase();

            if (extension !== 'pdf') {
                window.alert(
                    'Resume must be a PDF file.'
                );

                resumeInput.value = '';

                resumeFileName.textContent =
                    'PDF only · Maximum 5 MB';

                return;
            }

            if (
                file.size
                > 5 * 1024 * 1024
            ) {
                window.alert(
                    'Resume cannot exceed 5 MB.'
                );

                resumeInput.value = '';

                resumeFileName.textContent =
                    'PDF only · Maximum 5 MB';

                return;
            }

            resumeFileName.textContent =
                'Selected: ' + file.name;
        }
    );

    form.addEventListener(
        'submit',
        function (event) {
            if (!form.checkValidity()) {
                return;
            }

            const start = new Date(
                startDate.value + 'T00:00:00'
            );

            const end = new Date(
                endDate.value + 'T00:00:00'
            );

            if (end <= start) {
                event.preventDefault();

                window.alert(
                    'End date must be after start date.'
                );

                return;
            }

            const confirmed = window.confirm(
                'Submit this internship application '
                + 'to <?= h(
                    addslashes(
                        (string)$company['company_name']
                    )
                ) ?>?'
            );

            if (!confirmed) {
                event.preventDefault();

                return;
            }

            submitButton.disabled = true;

            submitButton.textContent =
                'Submitting...';

            submitButton.classList.add(
                'opacity-60',
                'cursor-not-allowed'
            );
        }
    );

    updateDuration();

    remarksCounter.textContent =
        remarks.value.length
        + ' / 2000';
});
</script>