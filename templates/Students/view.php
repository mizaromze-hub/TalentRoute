<?php
/**
 * templates/Students/view.php
 */
$studentId = (int)$student['id'];
$resume = $student['resume_path'] ?: ($student['resume'] ?? null);
$status = strtolower((string)($application['status'] ?? ''));
$isApproved = in_array($status, ['approved', 'offered', 'completed'], true);
?>

<script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-xs font-bold uppercase tracking-widest text-purple-500">
                <?= $role === 'student' ? 'Student / Profile' : 'Students / Profile' ?>
            </p>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white mt-1">
                Student Profile
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
                View academic, contact and internship information.
            </p>
        </div>

        <?php if ($role === 'student' || $role === 'admin'): ?>
            <a href="<?= $this->Url->build(
                $role === 'student'
                    ? ['controller' => 'Students', 'action' => 'edit']
                    : ['controller' => 'Students', 'action' => 'edit', $studentId]
            ) ?>"
               class="inline-flex justify-center bg-purple-600 hover:bg-purple-700 text-white font-bold px-5 py-3 rounded-xl">
                Edit Profile
            </a>
        <?php endif; ?>
    </div>

    <div class="grid xl:grid-cols-4 gap-6 items-start">
        <aside class="space-y-5">
            <div class="bg-gradient-to-br from-purple-700 to-indigo-700 rounded-2xl p-6 text-white shadow text-center">
                <div class="w-24 h-24 mx-auto rounded-full bg-white/15 border border-white/20 flex items-center justify-center text-3xl font-black">
                    <?= h(strtoupper(substr((string)$student['name'], 0, 1))) ?>
                </div>
                <p class="mt-4 text-lg font-black"><?= h($student['name']) ?></p>
                <p class="text-sm opacity-80"><?= h($student['matrix_number']) ?></p>
                <p class="mt-4 text-xs opacity-70">Student ID #<?= h((string)$studentId) ?></p>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl p-5 shadow text-center">
                <h2 class="font-black text-slate-900 dark:text-white">Verification QR</h2>
                <div id="studentQr" class="mt-4 inline-flex bg-white p-3 rounded-xl"></div>
                <a href="<?= h($verificationUrl) ?>"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="mt-3 block text-sm font-bold text-purple-600">
                    Open Verification Page
                </a>
            </div>
        </aside>

        <div class="xl:col-span-3 space-y-5">
            <section class="bg-white dark:bg-slate-800 rounded-2xl shadow p-5 md:p-7">
                <h2 class="font-black text-lg text-slate-900 dark:text-white">
                    Academic Information
                </h2>
                <div class="mt-5 grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php foreach ([
                        'Matrix Number' => $student['matrix_number'],
                        'Faculty' => $student['faculty'],
                        'Course' => $student['course'],
                        'Semester' => 'Semester ' . $student['semester'],
                        'Email' => $student['email'],
                        'Phone Number' => $student['phone_number'],
                    ] as $label => $value): ?>
                        <div class="rounded-xl bg-slate-50 dark:bg-slate-900/60 p-4">
                            <p class="text-xs text-slate-500"><?= h($label) ?></p>
                            <p class="font-bold text-slate-900 dark:text-white mt-1"><?= h((string)($value ?: '-')) ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>

            <section class="bg-white dark:bg-slate-800 rounded-2xl shadow p-5 md:p-7">
                <h2 class="font-black text-lg text-slate-900 dark:text-white">
                    Address
                </h2>
                <p class="mt-4 text-sm text-slate-600 dark:text-slate-300 leading-relaxed">
                    <?= h((string)($student['address'] ?: '-')) ?><br>
                    <?= h(trim((string)(($student['postcode'] ?? '') . ' ' . ($student['city'] ?? '')))) ?><br>
                    <?= h((string)($student['state'] ?: '')) ?>
                </p>
            </section>

            <section class="bg-white dark:bg-slate-800 rounded-2xl shadow p-5 md:p-7">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="font-black text-lg text-slate-900 dark:text-white">
                            Resume
                        </h2>
                        <p class="text-sm text-slate-500 mt-1">
                            <?= $resume ? 'A PDF resume is available.' : 'No resume has been uploaded.' ?>
                        </p>
                    </div>

                    <?php if ($resume): ?>
                        <a href="<?= $this->Url->build('/files/' . rawurlencode((string)$resume)) ?>"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="inline-flex justify-center border border-purple-600 text-purple-600 font-bold px-5 py-3 rounded-xl">
                            View Resume
                        </a>
                    <?php endif; ?>
                </div>
            </section>

            <section class="bg-white dark:bg-slate-800 rounded-2xl shadow p-5 md:p-7">
                <h2 class="font-black text-lg text-slate-900 dark:text-white">
                    Latest Internship Application
                </h2>

                <?php if (!$application): ?>
                    <p class="mt-4 text-sm text-slate-500">No application submitted yet.</p>
                <?php else: ?>
                    <div class="mt-5 grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="rounded-xl bg-slate-50 dark:bg-slate-900/60 p-4">
                            <p class="text-xs text-slate-500">Company</p>
                            <p class="font-bold mt-1"><?= h($application['display_company']) ?></p>
                        </div>
                        <div class="rounded-xl bg-slate-50 dark:bg-slate-900/60 p-4">
                            <p class="text-xs text-slate-500">Status</p>
                            <p class="font-bold mt-1"><?= h(ucfirst((string)$application['status'])) ?></p>
                        </div>
                        <div class="rounded-xl bg-slate-50 dark:bg-slate-900/60 p-4">
                            <p class="text-xs text-slate-500">Start Date</p>
                            <p class="font-bold mt-1"><?= h((string)($application['start_date'] ?: '-')) ?></p>
                        </div>
                        <div class="rounded-xl bg-slate-50 dark:bg-slate-900/60 p-4">
                            <p class="text-xs text-slate-500">End Date</p>
                            <p class="font-bold mt-1"><?= h((string)($application['end_date'] ?: '-')) ?></p>
                        </div>
                    </div>

                    <?php if ($role === 'student' && $isApproved): ?>
                        <a href="<?= $this->Url->build([
                            'controller' => 'Internships',
                            'action' => 'letter',
                            $application['id'],
                        ]) ?>"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="mt-5 inline-flex bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-5 py-3 rounded-xl">
                            Open Official Letter
                        </a>
                    <?php endif; ?>
                <?php endif; ?>
            </section>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const container = document.getElementById('studentQr');
    if (container && typeof QRCode !== 'undefined') {
        new QRCode(container, {
            text: <?= json_encode(
                $verificationUrl,
                JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
            ) ?>,
            width: 180,
            height: 180,
            correctLevel: QRCode.CorrectLevel.H
        });
    }
});
</script>
