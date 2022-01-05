<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Kinmuhyou $kinmuhyou
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Kinmuhyou'), ['action' => 'edit', $kinmuhyou->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Kinmuhyou'), ['action' => 'delete', $kinmuhyou->id], ['confirm' => __('Are you sure you want to delete # {0}?', $kinmuhyou->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Kinmuhyou'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Kinmuhyou'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="kinmuhyou view content">
            <h3><?= h($kinmuhyou->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('User') ?></th>
                    <td><?= $kinmuhyou->has('user') ? $this->Html->link($kinmuhyou->user->username, ['controller' => 'Users', 'action' => 'view', $kinmuhyou->user->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($kinmuhyou->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Syukin Time Id') ?></th>
                    <td><?= $this->Number->format($kinmuhyou->syukin_time_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Taikin Time Id') ?></th>
                    <td><?= $this->Number->format($kinmuhyou->taikin_time_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Kinmu Date') ?></th>
                    <td><?= h($kinmuhyou->kinmu_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Syukin Time') ?></th>
                    <td><?= h($kinmuhyou->syukin_time) ?></td>
                </tr>
                <tr>
                    <th><?= __('Taikin Time') ?></th>
                    <td><?= h($kinmuhyou->taikin_time) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
