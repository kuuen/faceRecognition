<?= $this->Form->create() ?>
    <fieldset>
    <?php
        echo $this->Form->control('id');
    ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
<?= $this->Form->end() ?>
