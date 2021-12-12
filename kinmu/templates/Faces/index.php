<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Face[]|\Cake\Collection\CollectionInterface $faces
 */
?>
<div class="faces index content">
    <?= $this->Html->link(__('New Face'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Faces') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('user_id') ?></th>
                    <th><?= $this->Paginator->sort('facepath') ?></th>
                    <th><?= $this->Paginator->sort('created') ?></th>
                    <th><?= $this->Paginator->sort('modified') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($faces as $face): ?>
                <tr>
                    <td><?= $this->Number->format($face->id) ?></td>
                    <td><?= $face->has('user') ? $this->Html->link($face->user->name, ['controller' => 'Users', 'action' => 'view', $face->user->id]) : '' ?></td>
                    <td><?= h($face->facepath) ?></td>
                    <td><?= h($face->created) ?></td>
                    <td><?= h($face->modified) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $face->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $face->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $face->id], ['confirm' => __('Are you sure you want to delete # {0}?', $face->id)]) ?>
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
