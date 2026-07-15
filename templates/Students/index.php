<?php
/** @var array $studentsList */
/** @var array $u */
/** @var string $search */
$isAdmin = strtolower((string)($u['role'] ?? '')) === 'admin';
?>
<div class="space-y-6">
    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white">Student List</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400">Complete student information.</p>
        </div>

        <?php if ($isAdmin): ?>
            <div class="flex flex-col sm:flex-row gap-3">
                <?= $this->Form->create(null, ['type' => 'get', 'class' => 'flex gap-2']) ?>
                    <?= $this->Form->control('search', [
                        'label' => false,
                        'value' => $search,
                        'placeholder' => 'Search name, matrix, faculty',
                        'class' => 'w-72 max-w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-900 px-3 py-2'
                    ]) ?>
                    <?= $this->Form->button('Search', ['class' => 'rounded-lg bg-gradient-to-r from-purple-600 to-pink-500 hover:from-purple-700 hover:to-pink-600 text-white font-bold px-4 py-2']) ?>
                <?= $this->Form->end() ?>

                <?= $this->Html->link('+ Add Student', ['controller' => 'Students', 'action' => 'add'], [
                    'class' => 'inline-flex items-center justify-center rounded-lg bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 hover:bg-purple-700 text-white font-bold px-5 py-2'
                ]) ?>
            </div>
        <?php endif; ?>
    </div>

    <div class="overflow-x-auto bg-white dark:bg-slate-800 rounded-2xl rounded-2xl shadow-xl border border-purple-100">
        <table class="w-full text-sm">
            <thead class="bg-gradient-to-r from-purple-100 to-pink-100 text-slate-800">
                <tr>
                    <th class="p-4 text-left">Name</th>
                    <th class="p-4 text-left">Matrix</th>
                    <th class="p-4 text-left">Faculty</th>
                    <th class="p-4 text-left">Course</th>
                    <th class="p-4 text-left">Semester</th>
                    <th class="p-4 text-left">Phone</th>
                    <th class="p-4 text-left">Email</th>
                    <th class="p-4 text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!$studentsList): ?>
                    <tr><td colspan="8" class="p-8 text-center text-slate-500">No students found.</td></tr>
                <?php endif; ?>
                <?php foreach ($studentsList as $student): ?>
                    <tr class="border-t border-slate-200 dark:border-slate-700 hover:bg-purple-50 transition duration-300">
                        <td class="p-4 font-bold"><?= h($student['name']) ?></td>
                        <td class="p-4"><?= h($student['matrix_number']) ?></td>
                        <td class="p-4"><?= h($student['faculty']) ?></td>
                        <td class="p-4"><?= h($student['course']) ?></td>
                        <td class="p-4"><?= h($student['semester']) ?></td>
                        <td class="p-4"><?= h($student['phone_number']) ?></td>
                        <td class="p-4"><?= h($student['email']) ?></td>
                        <td class="p-4 text-right">
                            <?= $this->Html->link(
                                'Edit',
                                ['controller' => 'Students', 'action' => 'edit', $student['id']],
                                [
                                    'class' => 'inline-flex items-center justify-center px-4 py-2 rounded-lg bg-purple-100 text-purple-700 hover:bg-purple-200 hover:text-purple-800 font-semibold transition duration-300'
                                ]
                            ) ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
