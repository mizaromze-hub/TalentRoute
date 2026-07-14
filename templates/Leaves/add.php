<?php
/**
 * @var \App\View\AppView $this
 * @var array $user
 * @var array $student
 * @var array $application
 * @var int $totalAllowed
 * @var int $usedDays
 * @var int $remainingDays
 */

$formData = $this->getRequest()->getData();

$selectedType = (string)(
    $formData['leave_type'] ?? ''
);

$startValue = (string)(
    $formData['start_date'] ?? ''
);

$endValue = (string)(
    $formData['end_date'] ?? ''
);

$reasonValue = (string)(
    $formData['reason'] ?? ''
);

$percentage = $totalAllowed > 0
    ? min(
        100,
        round(($usedDays / $totalAllowed) * 100)
    )
    : 0;

$minimumDate =
    $application['start_date']
    ?: date('Y-m-d');

$maximumDate =
    $application['end_date']
    ?: null;
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
                Student / Leave
            </p>

            <h1
                class="text-2xl font-black
                text-slate-900 dark:text-white mt-1"
            >
                Apply for Leave
            </h1>

            <p
                class="text-sm text-slate-500
                dark:text-slate-400 mt-1"
            >
                Submit a leave request to your internship company.
            </p>
        </div>

        <a
            href="<?= $this->Url->build([
                'controller' => 'Leaves',
                'action' => 'index',
            ]) ?>"
            class="inline-flex items-center justify-center
            border border-slate-300
            dark:border-slate-700
            text-slate-700 dark:text-slate-200
            hover:bg-slate-100
            dark:hover:bg-slate-800
            font-bold px-4 py-2 rounded-xl"
        >
            ← Leave History
        </a>
    </div>

    <div
        class="grid xl:grid-cols-4
        gap-6 items-start"
    >
        <aside class="space-y-5">

            <div
                class="rounded-2xl
                bg-gradient-to-br
                from-purple-700 to-indigo-700
                text-white p-5 shadow"
            >
                <p
                    class="text-xs font-bold uppercase
                    tracking-wider opacity-75"
                >
                    Leave Balance
                </p>

                <div
                    class="mt-3 flex items-end
                    justify-between"
                >
                    <div>
                        <p class="text-4xl font-black">
                            <?= h((string)$remainingDays) ?>
                        </p>

                        <p class="text-sm opacity-80">
                            days remaining
                        </p>
                    </div>

                    <p class="text-sm font-bold">
                        <?= h((string)$usedDays) ?>
                        /
                        <?= h((string)$totalAllowed) ?>
                        used
                    </p>
                </div>

                <div
                    class="mt-4 h-3 rounded-full
                    bg-white/20 overflow-hidden"
                >
                    <div
                        class="h-full bg-white
                        rounded-full"
                        style="width:
                            <?= h((string)$percentage) ?>%"
                    ></div>
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
                    Internship Details
                </h2>

                <div
                    class="mt-4 space-y-3 text-sm
                    text-slate-600 dark:text-slate-300"
                >
                    <div>
                        <p class="text-xs text-slate-500">
                            Company
                        </p>

                        <p class="font-bold">
                            <?= h(
                                $application['display_company']
                            ) ?>
                        </p>
                    </div>

                    <div>
                        <p class="text-xs text-slate-500">
                            Internship Period
                        </p>

                        <p class="font-bold">
                            <?= h(
                                $application['start_date']
                                ?: '-'
                            ) ?>

                            until

                            <?= h(
                                $application['end_date']
                                ?: '-'
                            ) ?>
                        </p>
                    </div>
                </div>
            </div>

            <div
                class="bg-amber-50
                dark:bg-amber-900/20
                border border-amber-200
                dark:border-amber-800
                rounded-2xl p-5"
            >
                <h2
                    class="font-black text-amber-700
                    dark:text-amber-300"
                >
                    Important
                </h2>

                <ul
                    class="mt-3 space-y-2 text-sm
                    text-amber-700
                    dark:text-amber-200"
                >
                    <li>
                        Medical leave requires an MC.
                    </li>

                    <li>
                        Maximum attachment size is 5 MB.
                    </li>

                    <li>
                        Approved or rejected requests
                        cannot be changed.
                    </li>
                </ul>
            </div>
        </aside>

        <div
            class="xl:col-span-3
            bg-white dark:bg-slate-800
            rounded-2xl shadow overflow-hidden"
        >
            <?= $this->Form->create(null, [
                'type' => 'file',
                'id' => 'leaveApplicationForm',
            ]) ?>

            <div class="p-5 md:p-7 space-y-6">

                <div
                    class="grid md:grid-cols-2 gap-5"
                >
                    <div>
                        <label
                            for="leave_type"
                            class="block text-sm font-bold
                            text-slate-700
                            dark:text-slate-200 mb-2"
                        >
                            Leave Type *
                        </label>

                        <select
                            id="leave_type"
                            name="leave_type"
                            required
                            class="w-full p-3 rounded-xl
                            bg-slate-50 dark:bg-slate-900
                            border border-slate-200
                            dark:border-slate-700
                            text-slate-900 dark:text-white"
                        >
                            <option value="">
                                Select Leave Type
                            </option>

                            <?php foreach ([
                                'medical' => 'Medical Leave',
                                'emergency' => 'Emergency Leave',
                                'personal' => 'Personal Leave',
                                'family' => 'Family Leave',
                                'other' => 'Other Leave',
                            ] as $value => $label): ?>
                                <option
                                    value="<?= h($value) ?>"
                                    <?= $selectedType === $value
                                        ? 'selected'
                                        : ''
                                    ?>
                                >
                                    <?= h($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div>
                        <label
                            class="block text-sm font-bold
                            text-slate-700
                            dark:text-slate-200 mb-2"
                        >
                            Estimated Duration
                        </label>

                        <div
                            class="h-[50px] rounded-xl
                            bg-purple-50
                            dark:bg-purple-900/20
                            border border-purple-200
                            dark:border-purple-800
                            flex items-center px-4"
                        >
                            <span
                                id="durationDisplay"
                                class="font-black text-purple-700
                                dark:text-purple-300"
                            >
                                Select dates
                            </span>
                        </div>
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
                            Start Date *
                        </label>

                        <input
                            id="start_date"
                            type="date"
                            name="start_date"
                            value="<?= h($startValue) ?>"
                            min="<?= h($minimumDate) ?>"
                            <?= $maximumDate
                                ? 'max="' . h($maximumDate) . '"'
                                : ''
                            ?>
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
                            End Date *
                        </label>

                        <input
                            id="end_date"
                            type="date"
                            name="end_date"
                            value="<?= h($endValue) ?>"
                            min="<?= h($minimumDate) ?>"
                            <?= $maximumDate
                                ? 'max="' . h($maximumDate) . '"'
                                : ''
                            ?>
                            required
                            class="w-full p-3 rounded-xl
                            bg-slate-50 dark:bg-slate-900
                            border border-slate-200
                            dark:border-slate-700
                            text-slate-900 dark:text-white"
                        >
                    </div>
                </div>

                <div>
                    <label
                        for="reason"
                        class="block text-sm font-bold
                        text-slate-700
                        dark:text-slate-200 mb-2"
                    >
                        Reason *
                    </label>

                    <textarea
                        id="reason"
                        name="reason"
                        required
                        minlength="10"
                        maxlength="1000"
                        rows="5"
                        placeholder="Explain your reason for requesting leave..."
                        class="w-full p-3 rounded-xl
                        bg-slate-50 dark:bg-slate-900
                        border border-slate-200
                        dark:border-slate-700
                        text-slate-900 dark:text-white"
                    ><?= h($reasonValue) ?></textarea>

                    <div
                        class="mt-1 flex justify-between
                        text-xs text-slate-500"
                    >
                        <span>
                            Minimum 10 characters
                        </span>

                        <span id="reasonCounter">
                            0 / 1000
                        </span>
                    </div>
                </div>

                <div>
                    <label
                        for="mc_document"
                        id="attachmentBox"
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
                            📎
                        </div>

                        <p
                            class="mt-3 font-black
                            text-slate-900 dark:text-white"
                        >
                            Supporting Document
                        </p>

                        <p
                            id="attachmentName"
                            class="mt-1 text-sm text-slate-500
                            dark:text-slate-400"
                        >
                            PDF, JPG, JPEG or PNG · Maximum 5 MB
                        </p>

                        <input
                            id="mc_document"
                            type="file"
                            name="mc_document"
                            accept=".pdf,.jpg,.jpeg,.png,
                                application/pdf,image/jpeg,image/png"
                            class="hidden"
                        >
                    </label>

                    <p
                        id="medicalNotice"
                        class="hidden mt-2 text-xs
                        font-bold text-red-500"
                    >
                        Medical certificate is required
                        for medical leave.
                    </p>
                </div>

            </div>

            <div
                class="px-5 md:px-7 py-5
                bg-slate-50 dark:bg-slate-900/50
                border-t border-slate-200
                dark:border-slate-700
                flex flex-col-reverse sm:flex-row
                sm:items-center sm:justify-between gap-3"
            >
                <p
                    class="text-xs text-slate-500
                    dark:text-slate-400"
                >
                    The request will be sent to
                    <?= h($application['display_company']) ?>.
                </p>

                <div class="flex gap-3">
                    <a
                        href="<?= $this->Url->build([
                            'controller' => 'Leaves',
                            'action' => 'index',
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
                        id="submitLeaveButton"
                        type="submit"
                        class="inline-flex justify-center
                        bg-purple-600 hover:bg-purple-700
                        text-white px-6 py-3
                        rounded-xl font-bold shadow"
                    >
                        Submit Leave Request
                    </button>
                </div>
            </div>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const leaveType =
        document.getElementById('leave_type');

    const startDate =
        document.getElementById('start_date');

    const endDate =
        document.getElementById('end_date');

    const durationDisplay =
        document.getElementById('durationDisplay');

    const fileInput =
        document.getElementById('mc_document');

    const attachmentName =
        document.getElementById('attachmentName');

    const medicalNotice =
        document.getElementById('medicalNotice');

    const reason =
        document.getElementById('reason');

    const reasonCounter =
        document.getElementById('reasonCounter');

    const form =
        document.getElementById(
            'leaveApplicationForm'
        );

    const submitButton =
        document.getElementById(
            'submitLeaveButton'
        );

    const remainingDays =
        <?= json_encode($remainingDays) ?>;

    function updateMedicalRequirement() {
        const medical =
            leaveType.value === 'medical';

        fileInput.required = medical;

        if (medical) {
            medicalNotice.classList.remove('hidden');
        } else {
            medicalNotice.classList.add('hidden');
        }
    }

    function updateDuration() {
        if (!startDate.value || !endDate.value) {
            durationDisplay.textContent =
                'Select dates';

            return;
        }

        endDate.min = startDate.value;

        const start = new Date(
            startDate.value + 'T00:00:00'
        );

        const end = new Date(
            endDate.value + 'T00:00:00'
        );

        const milliseconds =
            end.getTime() - start.getTime();

        const days =
            Math.floor(
                milliseconds / 86400000
            ) + 1;

        if (days <= 0) {
            durationDisplay.textContent =
                'Invalid date range';

            durationDisplay.className =
                'font-black text-red-600';

            return;
        }

        durationDisplay.textContent =
            days + ' day(s)';

        if (days > remainingDays) {
            durationDisplay.textContent +=
                ' · Exceeds balance';

            durationDisplay.className =
                'font-black text-red-600';
        } else {
            durationDisplay.className =
                'font-black text-purple-700 '
                + 'dark:text-purple-300';
        }
    }

    leaveType.addEventListener(
        'change',
        updateMedicalRequirement
    );

    startDate.addEventListener(
        'change',
        updateDuration
    );

    endDate.addEventListener(
        'change',
        updateDuration
    );

    reason.addEventListener(
        'input',
        function () {
            reasonCounter.textContent =
                reason.value.length
                + ' / 1000';
        }
    );

    fileInput.addEventListener(
        'change',
        function () {
            const file = fileInput.files[0];

            if (!file) {
                attachmentName.textContent =
                    'PDF, JPG, JPEG or PNG · Maximum 5 MB';

                return;
            }

            const extension = file.name
                .split('.')
                .pop()
                .toLowerCase();

            const allowed = [
                'pdf',
                'jpg',
                'jpeg',
                'png'
            ];

            if (!allowed.includes(extension)) {
                window.alert(
                    'Supporting document must be '
                    + 'PDF, JPG, JPEG or PNG.'
                );

                fileInput.value = '';

                return;
            }

            if (file.size > 5 * 1024 * 1024) {
                window.alert(
                    'Supporting document cannot '
                    + 'exceed 5 MB.'
                );

                fileInput.value = '';

                return;
            }

            attachmentName.textContent =
                'Selected: ' + file.name;
        }
    );

    form.addEventListener(
        'submit',
        function (event) {
            if (!form.checkValidity()) {
                return;
            }

            if (
                leaveType.value === 'medical'
                && !fileInput.files.length
            ) {
                event.preventDefault();

                window.alert(
                    'Please upload a medical certificate.'
                );

                return;
            }

            const start = new Date(
                startDate.value + 'T00:00:00'
            );

            const end = new Date(
                endDate.value + 'T00:00:00'
            );

            const days =
                Math.floor(
                    (
                        end.getTime()
                        - start.getTime()
                    ) / 86400000
                ) + 1;

            if (days > remainingDays) {
                event.preventDefault();

                window.alert(
                    'This request exceeds your '
                    + 'remaining leave balance.'
                );

                return;
            }

            const confirmed = window.confirm(
                'Submit this leave request '
                + 'to your company?'
            );

            if (!confirmed) {
                event.preventDefault();

                return;
            }

            submitButton.disabled = true;
            submitButton.textContent = 'Submitting...';

            submitButton.classList.add(
                'opacity-60',
                'cursor-not-allowed'
            );
        }
    );

    updateMedicalRequirement();
    updateDuration();

    reasonCounter.textContent =
        reason.value.length + ' / 1000';
});
</script>