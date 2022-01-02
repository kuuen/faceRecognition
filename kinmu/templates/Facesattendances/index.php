<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Facesattendance[]|\Cake\Collection\CollectionInterface $facesattendances
 */
?>
<div class="facesattendances index content">
    <?= $this->Html->link(__('New Facesattendance'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Facesattendances') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('user_id') ?></th>
                    <th><?= $this->Paginator->sort('inout_time') ?></th>
                    <th><?= $this->Paginator->sort('inout_type') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($facesattendances as $facesattendance): ?>
                <tr>
                    <td><?= $this->Number->format($facesattendance->id) ?></td>
                    <td><?= $facesattendance->has('user') ? $this->Html->link($facesattendance->user->name, ['controller' => 'Users', 'action' => 'view', $facesattendance->user->id]) : '' ?></td>
                    <td><?= h($facesattendance->inout_time) ?></td>
                    <td><?= $this->Number->format($facesattendance->inout_type) ?></td>
                    <td><?= h($facesattendance->created) ?></td>
                    <td><?= h($facesattendance->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $facesattendance->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $facesattendance->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $facesattendance->id], ['confirm' => __('Are you sure you want to delete # {0}?', $facesattendance->id)]) ?>
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
