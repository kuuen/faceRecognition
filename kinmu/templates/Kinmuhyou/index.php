<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Kinmuhyou[]|\Cake\Collection\CollectionInterface $kinmuhyou
 */
?>
<div class="kinmuhyou index content">
    <?= $this->Html->link(__('New Kinmuhyou'), ['action' => 'add'], ['class' => 'button float-right']) ?>
    <h3><?= __('Kinmuhyou') ?></h3>
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><?= $this->Paginator->sort('id') ?></th>
                    <th><?= $this->Paginator->sort('user_id') ?></th>
                    <th><?= $this->Paginator->sort('kinmu_date') ?></th>
                    <th><?= $this->Paginator->sort('syukin_time') ?></th>
                    <th><?= $this->Paginator->sort('taikin_time') ?></th>
                    <th><?= $this->Paginator->sort('syukin_time_id') ?></th>
                    <th><?= $this->Paginator->sort('taikin_time_id') ?></th>
                    <th class="actions"><?= __('Actions') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($kinmuhyou as $kinmuhyou): ?>
                <tr>
                    <td><?= $this->Number->format($kinmuhyou->id) ?></td>
                    <td><?= $kinmuhyou->has('user') ? $this->Html->link($kinmuhyou->user->username, ['controller' => 'Users', 'action' => 'view', $kinmuhyou->user->id]) : '' ?></td>
                    <td><?= h($kinmuhyou->kinmu_date) ?></td>
                    <td><?= h($kinmuhyou->syukin_time) ?></td>
                    <td><?= h($kinmuhyou->taikin_time) ?></td>
                    <td><?= $this->Number->format($kinmuhyou->syukin_time_id) ?></td>
                    <td><?= $this->Number->format($kinmuhyou->taikin_time_id) ?></td>
                    <td class="actions">
                        <?= $this->Html->link(__('View'), ['action' => 'view', $kinmuhyou->id]) ?>
                        <?= $this->Html->link(__('Edit'), ['action' => 'edit', $kinmuhyou->id]) ?>
                        <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $kinmuhyou->id], ['confirm' => __('Are you sure you want to delete # {0}?', $kinmuhyou->id)]) ?>
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
