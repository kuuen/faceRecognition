<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Facesattendance $facesattendance
 * @var string[]|\Cake\Collection\CollectionInterface $users
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $facesattendance->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $facesattendance->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Facesattendances'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="facesattendances form content">
            <?= $this->Form->create($facesattendance) ?>
            <fieldset>
                <legend><?= __('Edit Facesattendance') ?></legend>
                <?php
                    echo $this->Form->control('user_id', ['options' => $users, 'empty' => true]);
                    echo $this->Form->control('inout_time');
                    echo $this->Form->control('inout_type');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
