<?php
/**
 * @var \App\View\AppView $this
 * @var array $student
 * @var bool $isVerified
 */

$status = strtolower(
    trim((string)($student['status'] ?? ''))
);

$statusLabels = [
    'approved' => 'Approved',
    'offered' => 'Approved',
    'completed' => 'Completed',
];

$statusLabel =
    $statusLabels[$status]
    ?? (
        $status !== ''
        ? ucfirst($status)
        : 'No Active Internship'
    );

$isActive = in_array(
    $status,
    ['approved', 'offered'],
    true
);
?>

<div
    class="min-h-[75vh] flex items-center
    justify-center py-8"
>
    <div
        class="w-full max-w-2xl bg-white
        dark:bg-slate-800 rounded-3xl
        shadow-xl overflow-hidden"
    >
        <div
            class="bg-gradient-to-r
            from-purple-700 to-indigo-700
            text-white p-7 text-center"
        >
            <div
                class="mx-auto w-16 h-16
                rounded-full bg-white/15
                border border-white/25
                flex items-center justify-center
                text-3xl"
            >
                ✓
            </div>

            <h1 class="text-2xl font-black mt-4">
                Verified Student
            </h1>

            <p class="text-sm opacity-80 mt-1">
                TalentRoute Internship Verification
            </p>
        </div>

        <div class="p-6 md:p-8">
            <div class="text-center">
                <h2
                    class="text-2xl font-black
                    text-slate-900 dark:text-white"
                >
                    <?= h($student['name']) ?>
                </h2>

                <p
                    class="text-sm text-slate-500
                    dark:text-slate-400 mt-1"
                >
                    <?= h($student['matrix_number']) ?>
                </p>

                <span
                    class="mt-4 inline-flex px-4 py-2
                    rounded-full text-sm font-bold
                    <?= $isActive
                        ? 'bg-emerald-100 text-emerald-700
                           dark:bg-emerald-900/30
                           dark:text-emerald-300'
                        : 'bg-slate-100 text-slate-700
                           dark:bg-slate-700
                           dark:text-slate-200'
                    ?>"
                >
                    <?= h($statusLabel) ?>
                </span>
            </div>

            <div
                class="mt-7 grid sm:grid-cols-2 gap-4"
            >
                <div
                    class="rounded-xl bg-slate-50
                    dark:bg-slate-900/60 p-4"
                >
                    <p
                        class="text-xs font-bold uppercase
                        text-slate-500"
                    >
                        Faculty
                    </p>

                    <p
                        class="font-semibold text-slate-900
                        dark:text-white mt-1"
                    >
                        <?= h($student['faculty'] ?: '-') ?>
                    </p>
                </div>

                <div
                    class="rounded-xl bg-slate-50
                    dark:bg-slate-900/60 p-4"
                >
                    <p
                        class="text-xs font-bold uppercase
                        text-slate-500"
                    >
                        Course
                    </p>

                    <p
                        class="font-semibold text-slate-900
                        dark:text-white mt-1"
                    >
                        <?= h($student['course'] ?: '-') ?>
                    </p>
                </div>

                <div
                    class="rounded-xl bg-slate-50
                    dark:bg-slate-900/60 p-4"
                >
                    <p
                        class="text-xs font-bold uppercase
                        text-slate-500"
                    >
                        Semester
                    </p>

                    <p
                        class="font-semibold text-slate-900
                        dark:text-white mt-1"
                    >
                        <?= h(
                            (string)(
                                $student['semester'] ?: '-'
                            )
                        ) ?>
                    </p>
                </div>

                <div
                    class="rounded-xl bg-slate-50
                    dark:bg-slate-900/60 p-4"
                >
                    <p
                        class="text-xs font-bold uppercase
                        text-slate-500"
                    >
                        Internship Company
                    </p>

                    <p
                        class="font-semibold text-slate-900
                        dark:text-white mt-1"
                    >
                        <?= h(
                            $student['display_company']
                            ?: 'Not assigned'
                        ) ?>
                    </p>
                </div>

                <div
                    class="rounded-xl bg-slate-50
                    dark:bg-slate-900/60 p-4"
                >
                    <p
                        class="text-xs font-bold uppercase
                        text-slate-500"
                    >
                        Start Date
                    </p>

                    <p
                        class="font-semibold text-slate-900
                        dark:text-white mt-1"
                    >
                        <?= h(
                            $student['start_date']
                            ?: '-'
                        ) ?>
                    </p>
                </div>

                <div
                    class="rounded-xl bg-slate-50
                    dark:bg-slate-900/60 p-4"
                >
                    <p
                        class="text-xs font-bold uppercase
                        text-slate-500"
                    >
                        End Date
                    </p>

                    <p
                        class="font-semibold text-slate-900
                        dark:text-white mt-1"
                    >
                        <?= h(
                            $student['end_date']
                            ?: '-'
                        ) ?>
                    </p>
                </div>
            </div>

            <div
                class="mt-6 rounded-xl border
                border-emerald-200 bg-emerald-50
                dark:border-emerald-800
                dark:bg-emerald-900/20 p-4"
            >
                <div class="flex items-start gap-3">
                    <span class="text-xl">🔒</span>

                    <div>
                        <p
                            class="font-bold text-emerald-700
                            dark:text-emerald-300"
                        >
                            Authentic TalentRoute Record
                        </p>

                        <p
                            class="text-xs text-emerald-600
                            dark:text-emerald-400 mt-1"
                        >
                            This information was retrieved
                            directly from the TalentRoute
                            database using a secure verification
                            link.
                        </p>
                    </div>
                </div>
            </div>

            <p
                class="mt-6 text-center text-xs
                text-slate-400"
            >
                Verified on
                <?= h(date('d M Y, h:i A')) ?>
            </p>
        </div>
    </div>
</div>