<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Kinmuhyou $kinmuhyou
 * @var string[]|\Cake\Collection\CollectionInterface $users
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $kinmuhyou->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $kinmuhyou->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Kinmuhyou'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="kinmuhyou form content">
            <?= $this->Form->create($kinmuhyou) ?>
            <fieldset>
                <legend><?= __('Edit Kinmuhyou') ?></legend>
                <?php
                    echo $this->Form->control('user_id', ['options' => $users]);
                    echo $this->Form->control('kinmu_date');
                    echo $this->Form->control('syukin_time', ['empty' => true]);
                    echo $this->Form->control('taikin_time', ['empty' => true]);
                    echo $this->Form->control('syukin_time_id');
                    echo $this->Form->control('taikin_time_id');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
