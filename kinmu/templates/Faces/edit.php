<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Face $face
 * @var string[]|\Cake\Collection\CollectionInterface $users
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $face->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $face->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Faces'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="faces form content">
            <?= $this->Form->create($face) ?>
            <fieldset>
                <legend><?= __('Edit Face') ?></legend>
                <?php
                    echo $this->Form->control('user_id', ['options' => $users, 'empty' => true]);
                    echo $this->Form->control('facepath');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
