<?php
/**
 * @var \App\View\AppView $this
 * @var array $applications
 * @var array $user
 * @var string $role
 */

$statusLabels = [
    'applied' => 'Pending',
    'pending' => 'Pending',
    'reviewing' => 'Pending',
    'interview' => 'Pending',
    'approved' => 'Approved',
    'offered' => 'Approved',
    'rejected' => 'Rejected',
    'completed' => 'Completed',
];

$statusClasses = [
    'applied' =>
        'bg-amber-100 text-amber-700
        dark:bg-amber-900/30 dark:text-amber-300',

    'pending' =>
        'bg-amber-100 text-amber-700
        dark:bg-amber-900/30 dark:text-amber-300',

    'reviewing' =>
        'bg-amber-100 text-amber-700
        dark:bg-amber-900/30 dark:text-amber-300',

    'interview' =>
        'bg-amber-100 text-amber-700
        dark:bg-amber-900/30 dark:text-amber-300',

    'approved' =>
        'bg-emerald-100 text-emerald-700
        dark:bg-emerald-900/30 dark:text-emerald-300',

    'offered' =>
        'bg-emerald-100 text-emerald-700
        dark:bg-emerald-900/30 dark:text-emerald-300',

    'rejected' =>
        'bg-red-100 text-red-700
        dark:bg-red-900/30 dark:text-red-300',

    'completed' =>
        'bg-blue-100 text-blue-700
        dark:bg-blue-900/30 dark:text-blue-300',
];

$pendingStatuses = [
    'applied',
    'pending',
    'reviewing',
    'interview',
];

$approvedStatuses = [
    'approved',
    'offered',
];
?>

<div
    class="flex flex-col sm:flex-row
    sm:items-center sm:justify-between
    gap-4 mb-6"
>
    <div>
        <h1
            class="text-2xl font-black
            text-slate-900 dark:text-white"
        >
            Internship Applications
        </h1>

        <p
            class="text-sm text-slate-500
            dark:text-slate-400"
        >
            Complete list of internship applications
        </p>
    </div>

    <?php if ($role === 'student'): ?>
        <a
            class="inline-flex items-center justify-center
            bg-purple-600 hover:bg-purple-700
            text-white font-semibold px-4 py-2
            rounded-lg transition"
            href="<?= $this->Url->build([
                'controller' => 'Internships',
                'action' => 'search',
            ]) ?>"
        >
            + Apply Internship
        </a>
    <?php endif; ?>
</div>

<div class="space-y-5">

    <?php if (empty($applications)): ?>
        <div
            class="bg-white dark:bg-slate-800
            p-10 rounded-2xl shadow text-center"
        >
            <p
                class="font-bold text-slate-800
                dark:text-white"
            >
                No applications found.
            </p>

            <p
                class="text-sm text-slate-500
                dark:text-slate-400 mt-1"
            >
                Internship applications will appear here.
            </p>
        </div>
    <?php endif; ?>

    <?php foreach ($applications as $application): ?>
        <?php
        $currentStatus = strtolower(
            trim((string)($application['status'] ?? 'pending'))
        );

        $statusLabel =
            $statusLabels[$currentStatus]
            ?? ucfirst($currentStatus);

        $statusClass =
            $statusClasses[$currentStatus]
            ?? 'bg-slate-100 text-slate-700
                dark:bg-slate-700 dark:text-slate-200';

        $isPending = in_array(
            $currentStatus,
            $pendingStatuses,
            true
        );

        $isApproved = in_array(
            $currentStatus,
            $approvedStatuses,
            true
        );

        $isRejected = $currentStatus === 'rejected';

        $resume =
            $application['resume_file']
            ?: ($application['resume_path'] ?? null);

        $applyDate =
            $application['apply_date']
            ?: (
                !empty($application['created'])
                ? substr(
                    (string)$application['created'],
                    0,
                    10
                )
                : '-'
            );

        $startDate =
            !empty($application['start_date'])
            ? (string)$application['start_date']
            : '-';

        $endDate =
            !empty($application['end_date'])
            ? (string)$application['end_date']
            : '-';

        $applicationId = (int)$application['id'];
        ?>

        <div
            class="bg-white dark:bg-slate-800
            rounded-2xl border border-slate-200
            dark:border-slate-700
            shadow-lg hover:shadow-xl
            transition-all duration-300
            p-6"
        >
            <div
                class="flex flex-col lg:flex-row
                lg:justify-between gap-6"
            >
                <div class="flex-1 min-w-0">

                    <div
                        class="flex flex-wrap
                        items-center justify-between
                        border-b border-slate-200
                        dark:border-slate-700
                        pb-4 mb-4"
                    >
                        <h2
                            class="font-black text-xl
                            text-slate-900 dark:text-white"
                        >
                            <?= h(
                                $application['display_company']
                                ?: 'Company'
                            ) ?>
                        </h2>

                        <span
                           class="px-6 py-3 rounded-full text-base font-bold <?= h($statusClass) ?>"
                        >
                            <?= h($statusLabel) ?>
                        </span>

                    </div>

                    <p
                        class="text-sm text-slate-800
                        dark:text-slate-200"
                    >
                        <strong>
                            <?= h(
                                $application['name']
                                ?: 'Student'
                            ) ?>
                        </strong>

                        <span class="text-slate-400">
                            ·
                        </span>

                        <?= h(
                            $application['matrix_number']
                            ?: '-'
                        ) ?>
                    </p>

                    <p
                        class="text-xs text-slate-500
                        dark:text-slate-400 mt-1"
                    >
                        <?= h(
                            $application['faculty']
                            ?: '-'
                        ) ?>

                        <span> | </span>

                        <?= h(
                            $application['course']
                            ?: '-'
                        ) ?>

                        <span> | Semester </span>

                        <?= h(
                            $application['semester']
                            ?: '-'
                        ) ?>

                        <span> | </span>

                        <?= h(
                            $application['phone_number']
                            ?: '-'
                        ) ?>
                    </p>

                    <div
                        class="mt-4 grid
                        sm:grid-cols-3 gap-3 text-sm"
                    >
                        <div
                            class="bg-gradient-to-r
                            from-purple-50
                            to-pink-50
                            dark:from-slate-900
                            dark:to-slate-800
                            border border-purple-100
                            dark:border-slate-700
                            rounded-xl
                            p-4
                            shadow-sm"
                        >
                            <p class="text-xs text-slate-500">
                                Apply Date
                            </p>

                            <p
                                class="font-semibold
                                text-slate-900 dark:text-white"
                            >
                                <?= h($applyDate) ?>
                            </p>
                        </div>

                        <div
                            class="bg-gradient-to-r
                            from-blue-50
                            to-indigo-50
                            dark:from-slate-900
                            dark:to-slate-800
                            border border-blue-100
                            dark:border-slate-700
                            rounded-xl
                            p-4"
                        >
                            <p class="text-xs text-slate-500">
                                Start Date
                            </p>

                            <p
                                class="font-semibold
                                text-slate-900 dark:text-white"
                            >
                                <?= h($startDate) ?>
                            </p>
                        </div>

                        <div
                            class="bg-gradient-to-r
                            from-emerald-50
                            to-green-50
                            dark:from-slate-900
                            dark:to-slate-800
                            border border-emerald-100
                            dark:border-slate-700
                            rounded-xl
                            p-4"
                        >
                            <p class="text-xs text-slate-500">
                                End Date
                            </p>

                            <p
                                class="font-semibold
                                text-slate-900 dark:text-white"
                            >
                                <?= h($endDate) ?>
                            </p>
                        </div>
                    </div>

                    <?php if (
                        !empty($application['remarks'])
                    ): ?>
                        <div
                            class="mt-5
                                bg-slate-50
                                dark:bg-slate-900
                                border border-slate-200
                                dark:border-slate-700
                                rounded-xl
                                p-4"
                        >
                            <p
                                class="text-xs font-bold
                                text-slate-500
                                dark:text-slate-400 mb-1"
                            >
                                Remarks
                            </p>

                            <p
                                class="text-sm text-slate-800
                                dark:text-slate-200
                                whitespace-pre-line"
                            >
                                <?= h(
                                    $application['remarks']
                                ) ?>
                            </p>
                        </div>
                    <?php endif; ?>

                    <div
                        class="mt-4 flex flex-wrap
                        gap-3 text-sm font-semibold"
                    >
                        <?php if ($resume): ?>
                            <a
                                class="inline-flex items-center
                                        gap-2
                                        bg-purple-600
                                        hover:bg-purple-700
                                        text-white
                                        rounded-lg
                                        px-4
                                        py-2
                                        transition"
                                target="_blank"
                                rel="noopener noreferrer"
                                href="<?= $this->Url->build(
                                    '/files/' .
                                    rawurlencode(
                                        (string)$resume
                                    )
                                ) ?>"
                            >
                                📄 View Resume
                            </a>
                        <?php else: ?>
                            <span class="text-slate-400">
                                No resume uploaded
                            </span>
                        <?php endif; ?>

                        <?php if (
                            $role === 'student'
                            && ($isApproved || $isRejected)
                        ): ?>
                            <a
                                class="inline-flex items-center gap-2
                                <?= $isApproved
                                    ? 'text-emerald-600 hover:text-emerald-700'
                                    : 'text-red-600 hover:text-red-700' ?>"
                                href="<?= $this->Url->build([
                                    'controller' => 'Internships',
                                    'action' => 'letter',
                                    $applicationId,
                                ]) ?>"
                                target="_blank"
                                rel="noopener noreferrer"
                            >
                                <?= $isApproved
                                    ? '📄 Download Acceptance Letter'
                                    : '📄 Download Rejection Letter' ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($role === 'company'): ?>
                    <div
                        class="w-full lg:w-80
                        lg:border-l
                        lg:border-slate-200
                        dark:lg:border-slate-700
                        lg:pl-6"
                    >
                        <?php if ($isPending): ?>
                            <h3
                                class="font-bold
                                text-slate-900
                                dark:text-white mb-1"
                            >
                                Application Decision
                            </h3>

                            <p
                                class="text-xs text-slate-500
                                dark:text-slate-400 mb-4"
                            >
                                This decision cannot be changed
                                after submission.
                            </p>

                            <?= $this->Form->create(null, [
                                'url' => [
                                    'controller' =>
                                        'Applications',

                                    'action' =>
                                        'updateStatus',

                                    $applicationId,
                                ],
                                'type' => 'post',
                                'class' =>
                                    'application-decision-form',
                            ]) ?>

                            <div class="mb-4">
                                <label
                                    for="status-<?= h(
                                        (string)$applicationId
                                    ) ?>"
                                    class="block text-sm
                                    font-semibold
                                    text-slate-700
                                    dark:text-slate-200
                                    mb-2"
                                >
                                    Decision
                                </label>

                                <?= $this->Form->select(
                                    'status',
                                    [
                                        'approved' =>
                                            'Approved',

                                        'rejected' =>
                                            'Rejected',
                                    ],
                                    [
                                        'id' =>
                                            'status-' .
                                            $applicationId,

                                        'empty' =>
                                            'Choose a decision',

                                        'required' => true,

                                        'class' =>
                                            'decision-select
                                            w-full p-3
                                            rounded-lg
                                            bg-slate-100
                                            dark:bg-slate-900
                                            border
                                            border-slate-200
                                            dark:border-slate-700
                                            text-slate-900
                                            dark:text-white',

                                        'data-application-id' =>
                                            $applicationId,
                                    ]
                                ) ?>
                            </div>

                            <div class="mb-4">
                                <label
                                    for="remarks-<?= h(
                                        (string)$applicationId
                                    ) ?>"
                                    class="block text-sm
                                    font-semibold
                                    text-slate-700
                                    dark:text-slate-200
                                    mb-2"
                                >
                                    Remarks
                                </label>

                                <?= $this->Form->textarea(
                                    'remarks',
                                    [
                                        'id' =>
                                            'remarks-' .
                                            $applicationId,

                                        'rows' => 4,
                                        'required' => true,

                                        'placeholder' =>
                                            'Enter remarks for the student.',

                                        'class' =>
                                            'w-full p-3
                                            rounded-lg
                                            bg-slate-100
                                            dark:bg-slate-900
                                            border
                                            border-slate-200
                                            dark:border-slate-700
                                            text-slate-900
                                            dark:text-white',
                                    ]
                                ) ?>
                            </div>

                            <?= $this->Form->button(
                                'Submit Decision',
                                [
                                    'type' => 'submit',

                                    'class' =>
                                        'w-full bg-purple-600
                                        hover:bg-purple-700
                                        text-white font-bold
                                        p-3 rounded-lg
                                        transition',
                                ]
                            ) ?>

                            <?= $this->Form->end() ?>

                        <?php elseif ($isApproved): ?>
                            <div
                                class="rounded-xl border
                                border-emerald-200
                                bg-emerald-50 p-5
                                dark:border-emerald-800
                                dark:bg-emerald-900/20"
                            >
                                <h3
                                    class="font-black
                                    text-emerald-700
                                    dark:text-emerald-300"
                                >
                                    ✓ Application Approved
                                </h3>

                                <p
                                    class="text-xs
                                    text-emerald-600
                                    dark:text-emerald-400 mt-1"
                                >
                                    Final decision submitted
                                </p>

                                <div
                                    class="mt-4 text-sm
                                    text-slate-700
                                    dark:text-slate-200"
                                >
                                    <p class="text-xs text-slate-500">
                                        Internship Period
                                    </p>

                                    <p class="font-semibold">
                                        <?= h($startDate) ?>
                                        until
                                        <?= h($endDate) ?>
                                    </p>
                                </div>
                            </div>

                        <?php elseif ($isRejected): ?>
                            <div
                                class="rounded-xl border
                                border-red-200 bg-red-50
                                p-5 dark:border-red-800
                                dark:bg-red-900/20"
                            >
                                <h3
                                    class="font-black
                                    text-red-700
                                    dark:text-red-300"
                                >
                                    ✕ Application Rejected
                                </h3>

                                <p
                                    class="text-xs
                                    text-red-600
                                    dark:text-red-400 mt-1"
                                >
                                    Final decision submitted
                                </p>
                            </div>

                        <?php else: ?>
                            <div
                                class="rounded-xl border
                                border-slate-200
                                bg-slate-50 p-5
                                dark:border-slate-700
                                dark:bg-slate-900/50"
                            >
                                <h3
                                    class="font-bold
                                    text-slate-800
                                    dark:text-white"
                                >
                                    <?= h($statusLabel) ?>
                                </h3>

                                <p
                                    class="text-sm
                                    text-slate-500
                                    dark:text-slate-400 mt-1"
                                >
                                    This application is no longer
                                    available for a new decision.
                                </p>
                            </div>
                        <?php endif; ?>

                        <?php if ($role === 'admin'): ?>
                            <div class="mt-4">
                                <?= $this->Form->postLink(
                                    'Delete Application',
                                    [
                                        'controller' =>
                                            'Applications',

                                        'action' =>
                                            'delete',

                                        $applicationId,
                                    ],
                                    [
                                        'confirm' =>
                                            'Are you sure you want to delete this application?',

                                        'class' =>
                                            'block w-full
                                            text-center py-2
                                            rounded-lg
                                            border border-red-200
                                            text-sm text-red-500
                                            hover:bg-red-50
                                            hover:text-red-600
                                            dark:border-red-900
                                            dark:hover:bg-red-900/20
                                            font-semibold',
                                    ]
                                ) ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const decisionSelects =
        document.querySelectorAll('.decision-select');

    decisionSelects.forEach(function (select) {
        const applicationId =
            select.dataset.applicationId;

        const dateFields =
            document.getElementById(
                'date-fields-' + applicationId
            );

        const startDateInput =
            document.getElementById(
                'start-date-' + applicationId
            );

        const endDateInput =
            document.getElementById(
                'end-date-' + applicationId
            );

        function updateDecisionFields() {
            const approved =
                select.value === 'approved';

            if (approved) {
                dateFields.classList.remove('hidden');
                startDateInput.required = true;
                endDateInput.required = true;
            } else {
                dateFields.classList.add('hidden');
                startDateInput.required = false;
                endDateInput.required = false;
                startDateInput.value = '';
                endDateInput.value = '';
            }
        }

        select.addEventListener(
            'change',
            updateDecisionFields
        );

        startDateInput.addEventListener(
            'change',
            function () {
                endDateInput.min =
                    startDateInput.value;

                if (
                    endDateInput.value
                    && endDateInput.value
                        <= startDateInput.value
                ) {
                    endDateInput.value = '';
                }
            }
        );

        updateDecisionFields();
    });

    const decisionForms =
        document.querySelectorAll(
            '.application-decision-form'
        );

    decisionForms.forEach(function (form) {
        form.addEventListener(
            'submit',
            function (event) {
                const select =
                    form.querySelector(
                        '.decision-select'
                    );

                const decision =
                    select.value === 'approved'
                        ? 'approve'
                        : 'reject';

                const confirmed =
                    window.confirm(
                        'Are you sure you want to '
                        + decision
                        + ' this application? '
                        + 'This decision cannot be changed.'
                    );

                if (!confirmed) {
                    event.preventDefault();
                }
            }
        );
    });
});
</script>