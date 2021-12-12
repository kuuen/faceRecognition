<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Face $face
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Face'), ['action' => 'edit', $face->id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Face'), ['action' => 'delete', $face->id], ['confirm' => __('Are you sure you want to delete # {0}?', $face->id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Faces'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Face'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="faces view content">
            <h3><?= h($face->id) ?></h3>
            <table>
                <tr>
                    <th><?= __('User') ?></th>
                    <td><?= $face->has('user') ? $this->Html->link($face->user->name, ['controller' => 'Users', 'action' => 'view', $face->user->id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Facepath') ?></th>
                    <td><?= h($face->facepath) ?></td>
                </tr>
                <tr>
                    <th><?= __('Id') ?></th>
                    <td><?= $this->Number->format($face->id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($face->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($face->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
