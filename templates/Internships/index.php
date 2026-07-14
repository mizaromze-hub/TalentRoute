<?php
/**
 * @var \App\View\AppView $this
 * @var iterable<\App\Model\Entity\Internship> $internships
 */
?>
<div class="internships index content">
    <?= $this->Html->link(__('New Internship'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Internships') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('student_id') ?></th>
                    <th><?= $this->Paginator->sort('company_id') ?></th>
                    <th><?= $this->Paginator->sort('status') ?></th>
                    <th><?= $this->Paginator->sort('start_date') ?></th>
                    <th><?= $this->Paginator->sort('end_date') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($internships as $internship): ?>
                <tr>
                    <td><?= $this->Number->format($internship->id) ?></td>
                    <td><?= $internship->hasValue('student') ? $this->Html->link($internship->student->name, ['controller' => 'Students', 'action' => 'view', $internship->student->id]) : '' ?></td>
                    <td><?= $internship->hasValue('company') ? $this->Html->link($internship->company->company_name, ['controller' => 'Companies', 'action' => 'view', $internship->company->id]) : '' ?></td>
                    <td><?= h($internship->status) ?></td>
                    <td><?= h($internship->start_date) ?></td>
                    <td><?= h($internship->end_date) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $internship->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $internship->id]) ?>
                        <?= $this->Form->postLink(
                            __('Delete'),
                            ['action' => 'delete', $internship->id],
                            [
                                'method' => 'delete',
                                'confirm' => __('Are you sure you want to delete # {0}?', $internship->id),
                            ]
                        ) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
    </div>
</div>