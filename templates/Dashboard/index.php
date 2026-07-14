<?php
/**
 * @var \App\View\AppView $this
 * @var array $userAttr
 * @var string $role
 * @var array|null $studentProfile
 * @var array|null $companyProfile
 * @var array $applications
 * @var array $leaves
 * @var array $stats
 * @var array $statusChartData
 * @var array $monthlyChartData
 * @var array $facultyChartData
 * @var array $leaveChartData
 * @var float|int $approvalRate
 * @var string|null $studentQrUrl
 * @var array|null $activeInternship
 */

$displayName =
    $userAttr['name']
    ?? $studentProfile['name']
    ?? $companyProfile['company_name']
    ?? ucfirst($role);

$statusLabels = [
    'applied' => 'Pending',
    'pending' => 'Pending',
    'reviewing' => 'Reviewing',
    'interview' => 'Interview',
    'approved' => 'Approved',
    'offered' => 'Approved',
    'rejected' => 'Rejected',
    'completed' => 'Completed',
];

$statusBadgeClasses = [
    'applied' =>
        'bg-amber-100 text-amber-700
        dark:bg-amber-900/30 dark:text-amber-300',

    'pending' =>
        'bg-amber-100 text-amber-700
        dark:bg-amber-900/30 dark:text-amber-300',

    'reviewing' =>
        'bg-blue-100 text-blue-700
        dark:bg-blue-900/30 dark:text-blue-300',

    'interview' =>
        'bg-violet-100 text-violet-700
        dark:bg-violet-900/30 dark:text-violet-300',

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
        'bg-slate-100 text-slate-700
        dark:bg-slate-700 dark:text-slate-200',
];
?>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php if ($role === 'student'): ?>
    <script
        src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"
    ></script>
<?php endif; ?>

<div class="space-y-6">

    <div
        class="rounded-2xl bg-gradient-to-r
        from-purple-700 to-indigo-700
        text-white p-6 shadow-lg"
    >
        <div
            class="flex flex-col md:flex-row
            md:items-center md:justify-between gap-4"
        >
            <div>
                <p
                    class="text-xs font-bold uppercase
                    tracking-widest opacity-75"
                >
                    TalentRoute Dashboard
                </p>

                <h1 class="text-2xl md:text-3xl font-black mt-1">
                    Selamat Datang,
                    <?= h((string)$displayName) ?>!
                </h1>

                <p class="opacity-80 text-sm mt-1">
                    Panel <?= h(strtoupper($role)) ?>
                    · <?= h(date('d M Y')) ?>
                </p>
            </div>

            <div
                class="rounded-xl bg-white/10
                border border-white/20 px-5 py-3"
            >
                <p class="text-xs opacity-75">
                    Approval Rate
                </p>

                <p class="text-3xl font-black">
                    <?= h((string)$approvalRate) ?>%
                </p>
            </div>
        </div>
    </div>

    <?php if ($role === 'admin'): ?>

        <div
            class="grid grid-cols-2
            lg:grid-cols-5 gap-4"
        >
            <?php
            $adminCards = [
                [
                    'label' => 'Students',
                    'value' => $stats['students'] ?? 0,
                    'icon' => '🎓',
                ],
                [
                    'label' => 'Companies',
                    'value' => $stats['companies'] ?? 0,
                    'icon' => '🏢',
                ],
                [
                    'label' => 'Applications',
                    'value' => $stats['applications'] ?? 0,
                    'icon' => '📁',
                ],
                [
                    'label' => 'Leave Requests',
                    'value' => $stats['leaves'] ?? 0,
                    'icon' => '📅',
                ],
                [
                    'label' => 'Approval Rate',
                    'value' => $approvalRate . '%',
                    'icon' => '📈',
                ],
            ];
            ?>

            <?php foreach ($adminCards as $card): ?>
                <div
                    class="bg-white dark:bg-slate-800
                    rounded-xl p-5 shadow"
                >
                    <div
                        class="flex items-center
                        justify-between gap-3"
                    >
                        <div>
                            <p
                                class="text-xs uppercase
                                text-slate-500"
                            >
                                <?= h($card['label']) ?>
                            </p>

                            <p
                                class="text-3xl font-black mt-1
                                text-slate-900 dark:text-white"
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

        <div class="grid lg:grid-cols-2 gap-5">
            <div
                class="bg-white dark:bg-slate-800
                p-5 rounded-xl shadow"
            >
                <h2
                    class="font-bold text-slate-900
                    dark:text-white mb-1"
                >
                    Application Status
                </h2>

                <p
                    class="text-xs text-slate-500
                    dark:text-slate-400 mb-4"
                >
                    Distribution of all application statuses.
                </p>

                <div class="h-72">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-800
                p-5 rounded-xl shadow"
            >
                <h2
                    class="font-bold text-slate-900
                    dark:text-white mb-1"
                >
                    Applications by Month
                </h2>

                <p
                    class="text-xs text-slate-500
                    dark:text-slate-400 mb-4"
                >
                    Number of internship applications submitted.
                </p>

                <div class="h-72">
                    <canvas id="monthChart"></canvas>
                </div>
            </div>
        </div>

        <div
            class="bg-white dark:bg-slate-800
            rounded-xl shadow overflow-hidden"
        >
            <div
                class="p-5 flex items-center
                justify-between gap-4"
            >
                <div>
                    <h2
                        class="font-bold text-slate-900
                        dark:text-white"
                    >
                        Recent Applications
                    </h2>

                    <p
                        class="text-xs text-slate-500
                        dark:text-slate-400"
                    >
                        Latest applications submitted by students.
                    </p>
                </div>

                <a
                    href="<?= $this->Url->build([
                        'controller' => 'Applications',
                        'action' => 'index',
                    ]) ?>"
                    class="text-sm font-bold text-purple-600
                    hover:text-purple-700"
                >
                    View All
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead
                        class="bg-slate-100
                        dark:bg-slate-900"
                    >
                        <tr>
                            <th class="p-3 text-left">
                                Student
                            </th>

                            <th class="p-3 text-left">
                                Company
                            </th>

                            <th class="p-3 text-left">
                                Status
                            </th>

                            <th class="p-3 text-left">
                                Apply Date
                            </th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php if (empty($applications)): ?>
                            <tr>
                                <td
                                    colspan="4"
                                    class="p-6 text-center
                                    text-slate-500"
                                >
                                    No applications found.
                                </td>
                            </tr>
                        <?php endif; ?>

                        <?php foreach ($applications as $application): ?>
                            <?php
                            $status = strtolower(
                                (string)$application['status']
                            );

                            $statusLabel =
                                $statusLabels[$status]
                                ?? ucfirst($status);

                            $statusClass =
                                $statusBadgeClasses[$status]
                                ?? 'bg-slate-100 text-slate-700';

                            $applyDate =
                                $application['apply_date']
                                ?? (
                                    !empty($application['created'])
                                    ? substr(
                                        (string)$application['created'],
                                        0,
                                        10
                                    )
                                    : '-'
                                );
                            ?>

                            <tr
                                class="border-t
                                dark:border-slate-700"
                            >
                                <td class="p-3">
                                    <p class="font-bold">
                                        <?= h($application['name']) ?>
                                    </p>

                                    <p class="text-xs text-slate-500">
                                        <?= h(
                                            $application[
                                                'matrix_number'
                                            ]
                                        ) ?>
                                    </p>
                                </td>

                                <td class="p-3">
                                    <?= h(
                                        $application['linked_company']
                                        ?: $application['company_name']
                                    ) ?>
                                </td>

                                <td class="p-3">
                                    <span
                                        class="inline-flex px-3 py-1
                                        rounded-full text-xs font-bold
                                        <?= h($statusClass) ?>"
                                    >
                                        <?= h($statusLabel) ?>
                                    </span>
                                </td>

                                <td class="p-3">
                                    <?= h($applyDate) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

    <?php elseif ($role === 'student'): ?>

        <div
            class="grid grid-cols-2
            lg:grid-cols-5 gap-4"
        >
            <?php
            $studentCards = [
                [
                    'label' => 'Applications',
                    'value' => $stats['applications'] ?? 0,
                    'icon' => '📁',
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
                [
                    'label' => 'Approval Rate',
                    'value' => $approvalRate . '%',
                    'icon' => '📈',
                ],
            ];
            ?>

            <?php foreach ($studentCards as $card): ?>
                <div
                    class="bg-white dark:bg-slate-800
                    rounded-xl p-5 shadow"
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
                                text-slate-900 dark:text-white"
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

        <div class="grid lg:grid-cols-2 gap-5">
            <div
                class="bg-white dark:bg-slate-800
                p-5 rounded-xl shadow"
            >
                <h2
                    class="font-bold text-slate-900
                    dark:text-white mb-1"
                >
                    My Application Status
                </h2>

                <p
                    class="text-xs text-slate-500
                    dark:text-slate-400 mb-4"
                >
                    Percentage and distribution of your applications.
                </p>

                <div class="h-72">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-800
                p-5 rounded-xl shadow"
            >
                <h2
                    class="font-bold text-slate-900
                    dark:text-white mb-1"
                >
                    Applications by Month
                </h2>

                <p
                    class="text-xs text-slate-500
                    dark:text-slate-400 mb-4"
                >
                    Your internship application activity.
                </p>

                <div class="h-72">
                    <canvas id="monthChart"></canvas>
                </div>
            </div>
        </div>

        <?php if ($activeInternship): ?>
            <div
                class="rounded-xl border
                border-emerald-200 bg-emerald-50
                dark:border-emerald-800
                dark:bg-emerald-900/20 p-5"
            >
                <div
                    class="flex flex-col md:flex-row
                    md:items-center
                    md:justify-between gap-4"
                >
                    <div>
                        <p
                            class="text-xs font-bold uppercase
                            tracking-wider text-emerald-600
                            dark:text-emerald-400"
                        >
                            Approved Internship
                        </p>

                        <h2
                            class="text-xl font-black
                            text-emerald-800
                            dark:text-emerald-200 mt-1"
                        >
                            <?= h(
                                $activeInternship[
                                    'display_company'
                                ]
                            ) ?>
                        </h2>

                        <p
                            class="text-sm text-slate-600
                            dark:text-slate-300 mt-1"
                        >
                            <?= h(
                                $activeInternship['start_date']
                                ?: 'Start date not set'
                            ) ?>

                            until

                            <?= h(
                                $activeInternship['end_date']
                                ?: 'End date not set'
                            ) ?>
                        </p>
                    </div>

                    <a
                        href="<?= $this->Url->build([
                            'controller' => 'Internships',
                            'action' => 'letter',
                            $activeInternship['id'],
                        ]) ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="inline-flex items-center
                        justify-center bg-emerald-600
                        hover:bg-emerald-700 text-white
                        font-bold px-5 py-3 rounded-lg"
                    >
                        🖨️ Print Official Letter
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <div class="grid xl:grid-cols-3 gap-5">
            <div
                class="xl:col-span-2 bg-white
                dark:bg-slate-800 p-5
                rounded-xl shadow"
            >
                <div
                    class="flex items-center
                    justify-between mb-3"
                >
                    <h2
                        class="font-bold text-slate-900
                        dark:text-white"
                    >
                        My Applications
                    </h2>

                    <a
                        href="<?= $this->Url->build([
                            'controller' => 'Applications',
                            'action' => 'index',
                        ]) ?>"
                        class="text-sm font-bold
                        text-purple-600"
                    >
                        View All
                    </a>
                </div>

                <?php if (empty($applications)): ?>
                    <div
                        class="py-10 text-center
                        text-slate-500"
                    >
                        You have not submitted any application.
                    </div>
                <?php endif; ?>

                <?php foreach (
                    array_slice($applications, 0, 6)
                    as $application
                ): ?>
                    <?php
                    $status = strtolower(
                        (string)$application['status']
                    );

                    $statusLabel =
                        $statusLabels[$status]
                        ?? ucfirst($status);

                    $statusClass =
                        $statusBadgeClasses[$status]
                        ?? 'bg-slate-100 text-slate-700';

                    $applyDate =
                        $application['apply_date']
                        ?? (
                            !empty($application['created'])
                            ? substr(
                                (string)$application['created'],
                                0,
                                10
                            )
                            : '-'
                        );
                    ?>

                    <div
                        class="border-b dark:border-slate-700
                        py-4 flex flex-col sm:flex-row
                        sm:items-center sm:justify-between gap-3"
                    >
                        <div>
                            <p
                                class="font-bold text-slate-900
                                dark:text-white"
                            >
                                <?= h(
                                    $application['display_company']
                                ) ?>
                            </p>

                            <p
                                class="text-xs text-slate-500
                                dark:text-slate-400"
                            >
                                Applied: <?= h($applyDate) ?>
                            </p>
                        </div>

                        <div
                            class="flex items-center gap-3
                            sm:justify-end"
                        >
                            <span
                                class="inline-flex px-3 py-1
                                rounded-full text-xs font-bold
                                <?= h($statusClass) ?>"
                            >
                                <?= h($statusLabel) ?>
                            </span>

                            <?php if (
                                in_array(
                                    $status,
                                    ['approved', 'offered'],
                                    true
                                )
                            ): ?>
                                <a
                                    class="text-purple-600
                                    hover:text-purple-700
                                    text-xs font-bold"
                                    href="<?= $this->Url->build([
                                        'controller' =>
                                            'Internships',

                                        'action' =>
                                            'letter',

                                        $application['id'],
                                    ]) ?>"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                >
                                    Print Letter
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="space-y-5">
                <div
                    class="bg-white dark:bg-slate-800
                    p-5 rounded-xl shadow"
                >
                    <h2
                        class="font-bold text-slate-900
                        dark:text-white"
                    >
                        Student Profile
                    </h2>

                    <div
                        class="mt-4 space-y-2 text-sm
                        text-slate-600 dark:text-slate-300"
                    >
                        <p>
                            <strong>Name:</strong>
                            <?= h(
                                $studentProfile['name']
                                ?? '-'
                            ) ?>
                        </p>

                        <p>
                            <strong>Matrix:</strong>
                            <?= h(
                                $studentProfile['matrix_number']
                                ?? '-'
                            ) ?>
                        </p>

                        <p>
                            <strong>Faculty:</strong>
                            <?= h(
                                $studentProfile['faculty']
                                ?? '-'
                            ) ?>
                        </p>

                        <p>
                            <strong>Course:</strong>
                            <?= h(
                                $studentProfile['course']
                                ?? '-'
                            ) ?>
                        </p>

                        <p>
                            <strong>Semester:</strong>
                            <?= h(
                                (string)(
                                    $studentProfile['semester']
                                    ?? '-'
                                )
                            ) ?>
                        </p>
                    </div>

                    <a
                        href="<?= $this->Url->build([
                            'controller' => 'Students',
                            'action' => 'edit',
                            $studentProfile['id'] ?? null,
                        ]) ?>"
                        class="mt-4 inline-flex w-full
                        justify-center border
                        border-purple-600 text-purple-600
                        hover:bg-purple-50
                        dark:hover:bg-purple-900/20
                        font-bold px-4 py-2 rounded-lg"
                    >
                        Edit Profile
                    </a>

                    <?= $this->Form->create(null, [
                        'url' => [
                            'controller' => 'Students',
                            'action' => 'uploadResume',
                        ],
                        'type' => 'file',
                        'class' => 'mt-4',
                    ]) ?>

                    <label
                        class="block text-xs font-bold
                        text-slate-500 mb-2"
                    >
                        Upload Resume PDF
                    </label>

                    <input
                        type="file"
                        name="resume_file"
                        accept="application/pdf,.pdf"
                        required
                        class="text-xs w-full
                        border border-slate-200
                        dark:border-slate-700
                        rounded-lg p-2"
                    >

                    <button
                        type="submit"
                        class="mt-2 w-full bg-purple-600
                        hover:bg-purple-700 text-white
                        px-3 py-2 rounded-lg text-xs
                        font-bold"
                    >
                        Upload Resume
                    </button>

                    <?= $this->Form->end() ?>
                </div>

                <div
                    class="bg-white dark:bg-slate-800
                    p-5 rounded-xl shadow text-center"
                >
                    <h2
                        class="font-bold text-slate-900
                        dark:text-white"
                    >
                        Student Verification QR
                    </h2>

                    <p
                        class="text-xs text-slate-500
                        dark:text-slate-400 mt-1"
                    >
                        Scan this QR to verify student
                        internship information.
                    </p>

                    <div
                        id="studentQrCode"
                        class="mt-4 inline-flex bg-white
                        p-3 rounded-xl"
                    ></div>

                    <p
                        class="mt-3 text-xs font-semibold
                        text-slate-500"
                    >
                        <?= h(
                            $studentProfile['matrix_number']
                            ?? ''
                        ) ?>
                    </p>

                    <a
                        href="<?= h((string)$studentQrUrl) ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="mt-3 inline-flex
                        text-sm font-bold text-purple-600
                        hover:text-purple-700"
                    >
                        Open Verification Page
                    </a>
                </div>
            </div>
        </div>

    <?php else: ?>

        <div
            class="grid grid-cols-2
            lg:grid-cols-5 gap-4"
        >
            <?php
            $companyCards = [
                [
                    'label' => 'Applications',
                    'value' => $stats['applications'] ?? 0,
                    'icon' => '📁',
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
                [
                    'label' => 'Active Interns',
                    'value' => $stats['active_interns'] ?? 0,
                    'icon' => '🧑‍💻',
                ],
            ];
            ?>

            <?php foreach ($companyCards as $card): ?>
                <div
                    class="bg-white dark:bg-slate-800
                    rounded-xl p-5 shadow"
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
                                text-slate-900 dark:text-white"
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

        <div class="grid lg:grid-cols-2 gap-5">
            <div
                class="bg-white dark:bg-slate-800
                p-5 rounded-xl shadow"
            >
                <h2
                    class="font-bold text-slate-900
                    dark:text-white mb-1"
                >
                    Candidate Status
                </h2>

                <p
                    class="text-xs text-slate-500
                    dark:text-slate-400 mb-4"
                >
                    Distribution of applications received.
                </p>

                <div class="h-72">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>

            <div
                class="bg-white dark:bg-slate-800
                p-5 rounded-xl shadow"
            >
                <h2
                    class="font-bold text-slate-900
                    dark:text-white mb-1"
                >
                    Applications by Month
                </h2>

                <p
                    class="text-xs text-slate-500
                    dark:text-slate-400 mb-4"
                >
                    Monthly applications received by company.
                </p>

                <div class="h-72">
                    <canvas id="monthChart"></canvas>
                </div>
            </div>
        </div>

        <div
            class="bg-white dark:bg-slate-800
            p-5 rounded-xl shadow"
        >
            <div
                class="flex items-center
                justify-between mb-3"
            >
                <div>
                    <h2
                        class="font-bold text-slate-900
                        dark:text-white"
                    >
                        Recent Candidates
                    </h2>

                    <p
                        class="text-xs text-slate-500
                        dark:text-slate-400"
                    >
                        Latest students who applied to your company.
                    </p>
                </div>

                <a
                    href="<?= $this->Url->build([
                        'controller' => 'Applications',
                        'action' => 'index',
                    ]) ?>"
                    class="text-sm font-bold text-purple-600"
                >
                    Manage Applications
                </a>
            </div>

            <?php if (empty($applications)): ?>
                <div
                    class="py-10 text-center
                    text-slate-500"
                >
                    No candidate applications received.
                </div>
            <?php endif; ?>

            <?php foreach (
                array_slice($applications, 0, 8)
                as $application
            ): ?>
                <?php
                $status = strtolower(
                    (string)$application['status']
                );

                $statusLabel =
                    $statusLabels[$status]
                    ?? ucfirst($status);

                $statusClass =
                    $statusBadgeClasses[$status]
                    ?? 'bg-slate-100 text-slate-700';
                ?>

                <div
                    class="py-4 border-b
                    dark:border-slate-700
                    flex flex-col md:flex-row
                    md:items-center
                    md:justify-between gap-3"
                >
                    <div>
                        <p
                            class="font-bold text-slate-900
                            dark:text-white"
                        >
                            <?= h($application['name']) ?>

                            <span
                                class="font-normal
                                text-slate-400"
                            >
                                ·
                                <?= h(
                                    $application[
                                        'matrix_number'
                                    ]
                                ) ?>
                            </span>
                        </p>

                        <p
                            class="text-xs text-slate-500
                            dark:text-slate-400 mt-1"
                        >
                            <?= h($application['faculty']) ?>
                            /
                            <?= h($application['course']) ?>
                            /
                            Semester
                            <?= h(
                                (string)$application['semester']
                            ) ?>
                        </p>
                    </div>

                    <span
                        class="inline-flex self-start md:self-auto
                        px-3 py-1 rounded-full
                        text-xs font-bold
                        <?= h($statusClass) ?>"
                    >
                        <?= h($statusLabel) ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>

    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const statusData = <?= json_encode(
        $statusChartData,
        JSON_HEX_TAG
        | JSON_HEX_AMP
        | JSON_HEX_APOS
        | JSON_HEX_QUOT
    ) ?>;

    const monthData = <?= json_encode(
        $monthlyChartData,
        JSON_HEX_TAG
        | JSON_HEX_AMP
        | JSON_HEX_APOS
        | JSON_HEX_QUOT
    ) ?>;

    const statusLabelsMap = {
        applied: 'Pending',
        pending: 'Pending',
        reviewing: 'Reviewing',
        interview: 'Interview',
        approved: 'Approved',
        offered: 'Approved',
        rejected: 'Rejected',
        completed: 'Completed'
    };

    const statusCanvas =
        document.getElementById('statusChart');

    if (statusCanvas) {
        new Chart(statusCanvas, {
            type: 'doughnut',

            data: {
                labels: statusData.map(function (item) {
                    const key = String(
                        item.status || ''
                    ).toLowerCase();

                    return statusLabelsMap[key]
                        || key.charAt(0).toUpperCase()
                        + key.slice(1);
                }),

                datasets: [{
                    data: statusData.map(function (item) {
                        return Number(item.total);
                    }),

                    backgroundColor: [
                        '#f59e0b',
                        '#3b82f6',
                        '#8b5cf6',
                        '#10b981',
                        '#ef4444',
                        '#64748b'
                    ],

                    borderWidth: 0
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

    const monthCanvas =
        document.getElementById('monthChart');

    if (monthCanvas) {
        new Chart(monthCanvas, {
            type: 'bar',

            data: {
                labels: monthData.map(function (item) {
                    return item.month;
                }),

                datasets: [{
                    label: 'Applications',

                    data: monthData.map(function (item) {
                        return Number(item.total);
                    }),

                    backgroundColor: '#7c3aed',
                    borderRadius: 8
                }]
            },

            options: {
                responsive: true,
                maintainAspectRatio: false,

                scales: {
                    y: {
                        beginAtZero: true,

                        ticks: {
                            precision: 0
                        }
                    }
                },

                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    }

    <?php if (
        $role === 'student'
        && !empty($studentQrUrl)
    ): ?>
        const qrContainer =
            document.getElementById(
                'studentQrCode'
            );

        if (
            qrContainer
            && typeof QRCode !== 'undefined'
        ) {
            new QRCode(qrContainer, {
                text: <?= json_encode(
                    $studentQrUrl,
                    JSON_HEX_TAG
                    | JSON_HEX_AMP
                    | JSON_HEX_APOS
                    | JSON_HEX_QUOT
                ) ?>,

                width: 180,
                height: 180,
                correctLevel: QRCode.CorrectLevel.H
            });
        }
    <?php endif; ?>
});
</script>