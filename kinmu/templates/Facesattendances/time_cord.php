<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Facesattendance $facesattendance
 * @var string[]|\Cake\Collection\CollectionInterface $users
 */
?>

<h1>タイムカード</h1>

<?= $this->Form->create() ?>

    年:<?php echo $this->Form->input('year',
                            ["type"=>"select",
                            "options"=>$year_options,
                            "value"=>$year_selected
                            ]); ?>

    月：<?php echo $this->Form->input('month',
                        ["type"=>"select",
                         "options" => $month_options,
                         "value" =>$month_selected
                         ]);
                         ?>

    氏名:<?php echo $this->Form->input('user_id',
                        ["type"=>"select","empty"=>"選択してください",
                         "options"=>$user_options]);
                         ?>

    <?= $this->Form->button(__('表示')) ?>
<?= $this->Form->end() ?>

<?php if ($this->request->is('post')) { ?>
    <h2><?php echo $year ?>年<?php echo $month ?>月</h2>
    <table class="table table-bordered table-striped" id="table01">
        <tbody>
            <?php echo $this->Html->tableHeaders(array_values($header)); ?>
            <?php echo $this->Html->tableCells($calendar, array(), array(), true); ?>
        </tbody>
    </table>

<?php } ?>