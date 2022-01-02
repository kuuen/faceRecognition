<?= $this->Form->create() ?>
    <fieldset>
    <?php
        echo $this->Form->control('id');
        echo $this->Form->control('name');
    ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>
