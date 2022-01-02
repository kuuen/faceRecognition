<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Facesattendance $facesattendance
 * @var \Cake\Collection\CollectionInterface|string[] $users
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Facesattendances'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="facesattendances form content">
            <?= $this->Form->create($facesattendance) ?>
            <fieldset>
                <legend><?= __('Add Facesattendance') ?></legend>
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
