<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Facesattendance $facesattendance
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Facesattendance'), ['action' => 'edit', $facesattendance->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Facesattendance'), ['action' => 'delete', $facesattendance->id], ['confirm' => __('Are you sure you want to delete # {0}?', $facesattendance->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Facesattendances'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Facesattendance'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="facesattendances view content">
            <h3><?= h($facesattendance->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('User') ?></th>
                    <td><?= $facesattendance->has('user') ? $this->Html->link($facesattendance->user->name, ['controller' => 'Users', 'action' => 'view', $facesattendance->user->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($facesattendance->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Inout Type') ?></th>
                    <td><?= $this->Number->format($facesattendance->inout_type) ?></td>
                </tr>
                <tr>
                    <th><?= __('Inout Time') ?></th>
                    <td><?= h($facesattendance->inout_time) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($facesattendance->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($facesattendance->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
