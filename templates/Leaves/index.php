<?php
/**
 * @var \App\View\AppView $this
 * @var array $leaves
 * @var array $user
 * @var string $role
 * @var array $stats
 */

$statusLabels = [
    'pending' => 'Pending',
    'applied' => 'Pending',
    'reviewing' => 'Pending',
    'approved' => 'Approved',
    'rejected' => 'Rejected',
];

$statusClasses = [
    'pending' =>
        'bg-amber-100 text-amber-700
        dark:bg-amber-900/30 dark:text-amber-300',

    'applied' =>
        'bg-amber-100 text-amber-700
        dark:bg-amber-900/30 dark:text-amber-300',

    'reviewing' =>
        'bg-amber-100 text-amber-700
        dark:bg-amber-900/30 dark:text-amber-300',

    'approved' =>
        'bg-emerald-100 text-emerald-700
        dark:bg-emerald-900/30 dark:text-emerald-300',

    'rejected' =>
        'bg-red-100 text-red-700
        dark:bg-red-900/30 dark:text-red-300',
];

$typeLabels = [
    'medical' => 'Medical Leave',
    'emergency' => 'Emergency Leave',
    'personal' => 'Personal Leave',
    'family' => 'Family Leave',
    'other' => 'Other Leave',
];

$pendingStatuses = [
    'pending',
    'applied',
    'reviewing',
];
?>

<div class="space-y-6">

    <div
        <div class="flex flex-wrap items-center gap-3 border-b border-slate-200 dark:border-slate-700 pb-4 mb-4">
    >
        <div>
            <p
                class="text-xs font-bold uppercase
                tracking-widest text-purple-500"
            >
                TalentRoute / Leave
            </p>

            <h1
                class="text-2xl font-black
                text-slate-900 dark:text-white mt-1"
            >
                Leave Management
            </h1>

            <p
                class="text-sm text-slate-500
                dark:text-slate-400 mt-1"
            >
                <?= $role === 'student'
                    ? 'Track your internship leave requests.'
                    : (
                        $role === 'company'
                        ? 'Review leave requests from your interns.'
                        : 'View all internship leave records.'
                    )
                ?>
            </p>
        </div>

        <?php if ($role === 'student'): ?>
            <a
                href="<?= $this->Url->build([
                    'controller' => 'Leaves',
                    'action' => 'add',
                ]) ?>"
                class="inline-flex items-center justify-center
                bg-purple-600 hover:bg-purple-700
                text-white font-bold px-5 py-3
                rounded-xl transition shadow"
            >
                + Apply Leave
            </a>
        <?php endif; ?>
    </div>

    <div
        class="grid grid-cols-2
        lg:grid-cols-4 gap-4"
    >
        <?php
        $cards = [
            [
                'label' => 'Total Requests',
                'value' => $stats['total'] ?? 0,
                'icon' => '📄',
            ],
            [
                'label' => 'Pending',
                'value' => $stats['pending'] ?? 0,
                'icon' => '⏳',
            ],
            [
                'label' => 'Approved',
                'value' => $stats['approved'] ?? 0,
                'icon' => '✅',
            ],
            [
                'label' => 'Rejected',
                'value' => $stats['rejected'] ?? 0,
                'icon' => '❌',
            ],
        ];
        ?>

        <?php foreach ($cards as $card): ?>
            <div
                class="bg-white dark:bg-slate-800
                        rounded-2xl
                        border border-slate-200
                        dark:border-slate-700
                        shadow-lg
                        hover:shadow-xl
                        transition-all
                        duration-300
                        p-5"
            >
                <div
                    class="flex items-center
                    justify-between gap-3"
                >
                    <div>
                        <p
                            class="text-xs text-slate-500
                            dark:text-slate-400"
                        >
                            <?= h($card['label']) ?>
                        </p>

                        <p
                            class="text-3xl font-black
                            text-slate-900 dark:text-white mt-1"
                        >
                            <?= h((string)$card['value']) ?>
                        </p>
                    </div>

                    <span class="text-3xl">
                        <?= h($card['icon']) ?>
                    </span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if (empty($leaves)): ?>
        <div
            class="bg-white dark:bg-slate-800
            rounded-2xl p-12 shadow text-center"
        >
            <div class="text-5xl">
                📅
            </div>

            <h2
                class="mt-4 text-lg font-black
                text-slate-900 dark:text-white"
            >
                No Leave Requests
            </h2>

            <p
                class="mt-1 text-sm text-slate-500
                dark:text-slate-400"
            >
                Leave requests will appear here.
            </p>
        </div>
    <?php endif; ?>

    <div class="space-y-5">
        <?php foreach ($leaves as $leave): ?>
            <?php
            $status = strtolower(
                trim((string)($leave['status'] ?? 'pending'))
            );

            $statusLabel =
                $statusLabels[$status]
                ?? ucfirst($status);

            $statusClass =
                $statusClasses[$status]
                ?? 'bg-slate-100 text-slate-700';

            $leaveType = strtolower(
                trim((string)($leave['leave_type'] ?? 'other'))
            );

            $leaveTypeLabel =
                $typeLabels[$leaveType]
                ?? ucfirst($leaveType);

            $isPending = in_array(
                $status,
                $pendingStatuses,
                true
            );

            $mcPath = trim(
                (string)($leave['mc_doc_path'] ?? '')
            );

            $leaveId = (int)$leave['id'];
            ?>

            <article
                class="bg-white dark:bg-slate-800
                rounded-2xl
                border border-slate-200
                dark:border-slate-700
                shadow-lg hover:shadow-xl
                transition-all duration-300
                overflow-hidden"
            >
                <div
                    class="p-6 md:p-8
                    flex flex-col xl:flex-row gap-6"
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
                                class="text-xl font-black
                                text-slate-900 dark:text-white"
                            >
                                <?= h($leaveTypeLabel) ?>
                            </h2>

                            <span
                                class="inline-flex
                                        px-4 py-2
                                        rounded-full
                                        text-sm
                                        font-bold
                                <?= h($statusClass) ?>"
                            >
                                <?= h($statusLabel) ?>
                            </span>

                            <span
                                class="inline-flex px-3 py-1
                                rounded-full text-xs font-semibold
                                bg-slate-100 text-slate-600
                                dark:bg-slate-700
                                dark:text-slate-300"
                            >
                                Leave #<?= h((string)$leaveId) ?>
                            </span>
                        </div>

                        <?php if ($role !== 'student'): ?>
                            <div class="mt-3">
                                <p
                                    class="font-bold text-slate-900
                                    dark:text-white"
                                >
                                    <?= h($leave['name']) ?>
                                </p>

                                <p
                                    class="text-xs text-slate-500
                                    dark:text-slate-400"
                                >
                                    <?= h($leave['matrix_number']) ?>
                                    ·
                                    <?= h($leave['faculty']) ?>
                                    ·
                                    <?= h($leave['course']) ?>
                                    · Semester
                                    <?= h(
                                        (string)(
                                            $leave['semester']
                                            ?? '-'
                                        )
                                    ) ?>
                                </p>
                            </div>
                        <?php endif; ?>

                        <div
                            class="mt-4 grid
                            sm:grid-cols-2 lg:grid-cols-4 gap-3"
                        >
                            <div
                                class="rounded-xl
                                        bg-slate-50
                                        dark:bg-slate-900
                                        border border-slate-200
                                        dark:border-slate-700
                                        shadow-sm
                                        p-4"
                            >
                                <p class="text-xs text-slate-500">
                                    Start Date
                                </p>

                                <p
                                    class="font-bold text-slate-900
                                    dark:text-white mt-1"
                                >
                                    <?= h($leave['start_date']) ?>
                                </p>
                            </div>

                            <div
                                class="rounded-xl
                                        bg-slate-50
                                        dark:bg-slate-900
                                        border border-slate-200
                                        dark:border-slate-700
                                        shadow-sm
                                        p-4"
                            >
                                <p class="text-xs text-slate-500">
                                    End Date
                                </p>

                                <p
                                    class="font-bold text-slate-900
                                    dark:text-white mt-1"
                                >
                                    <?= h($leave['end_date']) ?>
                                </p>
                            </div>

                            <div
                                class="rounded-xl
                                        bg-slate-50
                                        dark:bg-slate-900
                                        border border-slate-200
                                        dark:border-slate-700
                                        shadow-sm
                                        p-4"
                            >
                                <p class="text-xs text-slate-500">
                                    Total Days
                                </p>

                                <p
                                    class="font-bold text-slate-900
                                    dark:text-white mt-1"
                                >
                                    <?= h(
                                        (string)$leave['total_days']
                                    ) ?>
                                    day(s)
                                </p>
                            </div>

                            <div
                                class="rounded-xl
                                        bg-slate-50
                                        dark:bg-slate-900
                                        border border-slate-200
                                        dark:border-slate-700
                                        shadow-sm
                                        p-4"
                            >
                                <p class="text-xs text-slate-500">
                                    Company
                                </p>

                                <p
                                    class="font-bold text-slate-900
                                    dark:text-white mt-1"
                                >
                                    <?= h(
                                        $leave['display_company']
                                        ?: '-'
                                    ) ?>
                                </p>
                            </div>
                        </div>

                        <div
                            class="mt-5
                                    rounded-xl
                                    bg-slate-50
                                    dark:bg-slate-900
                                    border border-slate-200
                                    dark:border-slate-700
                                    shadow-sm
                                    p-5"
                        >
                            <p
                                class="text-xs font-bold uppercase
                                tracking-wider text-slate-500
                                dark:text-slate-400"
                            >
                                Leave Reason
                            </p>

                            <p
                                class="mt-2 text-sm
                                text-slate-700
                                dark:text-slate-200
                                whitespace-pre-line"
                            >
                                <?= h($leave['reason']) ?>
                            </p>
                        </div>

                        <div
                            class="mt-4 flex flex-wrap gap-3"
                        >
                            <?php if ($mcPath !== ''): ?>
                                <a
                                    href="<?= $this->Url->build(
                                        '/files/mc/' .
                                        rawurlencode($mcPath)
                                    ) ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="inline-flex items-center
                                            bg-purple-600
                                            hover:bg-purple-700
                                            text-white
                                            rounded-lg
                                            px-4
                                            py-2
                                            text-sm
                                            font-semibold
                                            transition"
                                >
                                    📎 View Supporting Document
                                </a>

                            <?php else: ?>
    <span
        class="inline-flex items-center
               rounded-lg
               bg-slate-100
               dark:bg-slate-700
               px-3 py-2
               text-sm
               text-slate-500
               dark:text-slate-300"
    >
        No supporting document
    </span>
<?php endif; ?>

                        </div>
                    </div>

                    <?php if ($role === 'company'): ?>
                        <aside
                            class="w-full xl:w-80
                            xl:border-l
                            xl:border-slate-200
                            dark:xl:border-slate-700
                            xl:pl-6"
                        >
                            <?php if ($isPending): ?>
                                <h3
                                    class="font-black
                                    text-slate-900
                                    dark:text-white"
                                >
                                    Leave Decision
                                </h3>

                                <p
                                    class="text-xs text-slate-500
                                    dark:text-slate-400 mt-1 mb-4"
                                >
                                    A final decision cannot be changed.
                                </p>

                                <?= $this->Form->create(null, [
                                    'url' => [
                                        'controller' => 'Leaves',
                                        'action' => 'updateStatus',
                                        $leaveId,
                                    ],
                                    'type' => 'post',
                                    'class' => 'leave-decision-form',
                                ]) ?>

                                <label
                                    class="block text-sm font-bold
                                    text-slate-700
                                    dark:text-slate-200 mb-2"
                                >
                                    Decision
                                </label>

                                <?= $this->Form->select(
                                    'status',
                                    [
                                        'approved' => 'Approved',
                                        'rejected' => 'Rejected',
                                    ],
                                    [
                                        'empty' => 'Choose a decision',
                                        'required' => true,
                                        'class' =>
                                            'leave-decision-select
                                            w-full p-3 rounded-xl
                                            bg-slate-50
                                            dark:bg-slate-900
                                            border border-slate-200
                                            dark:border-slate-700
                                            text-slate-900
                                            dark:text-white',
                                    ]
                                ) ?>

                                <?= $this->Form->button(
                                    'Submit Decision',
                                    [
                                        'type' => 'submit',
                                        'class' =>
                                            'mt-4 w-full p-3
                                            rounded-xl bg-purple-600
                                            hover:bg-purple-700
                                            text-white font-bold',
                                    ]
                                ) ?>

                                <?= $this->Form->end() ?>

                            <?php elseif ($status === 'approved'): ?>
                                <div
                                    class="rounded-xl border
                                    border-emerald-200
                                    bg-emerald-50 p-5
                                    dark:border-emerald-800
                                    dark:bg-emerald-900/20"
                                >
                                    <p
                                        class="font-black
                                        text-emerald-700
                                        dark:text-emerald-300"
                                    >
                                        ✓ Leave Approved
                                    </p>

                                    <p
                                        class="text-xs
                                        text-emerald-600
                                        dark:text-emerald-400 mt-1"
                                    >
                                        Final decision submitted.
                                    </p>
                                </div>

                            <?php elseif ($status === 'rejected'): ?>
                                <div
                                    class="rounded-xl border
                                    border-red-200 bg-red-50 p-5
                                    dark:border-red-800
                                    dark:bg-red-900/20"
                                >
                                    <p
                                        class="font-black text-red-700
                                        dark:text-red-300"
                                    >
                                        ✕ Leave Rejected
                                    </p>

                                    <p
                                        class="text-xs text-red-600
                                        dark:text-red-400 mt-1"
                                    >
                                        Final decision submitted.
                                    </p>
                                </div>
                            <?php endif; ?>
                        </aside>
                    <?php endif; ?>


                </div>
            </article>
        <?php endforeach; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const forms = document.querySelectorAll(
        '.leave-decision-form'
    );

    forms.forEach(function (form) {
        form.addEventListener('submit', function (event) {
            const select = form.querySelector(
                '.leave-decision-select'
            );

            const action =
                select.value === 'approved'
                    ? 'approve'
                    : 'reject';

            const confirmed = window.confirm(
                'Are you sure you want to '
                + action
                + ' this leave request? '
                + 'This decision cannot be changed.'
            );

            if (!confirmed) {
                event.preventDefault();
            }
        });
    });
});
</script>