<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CustomerFile $customerFile
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Customer Files'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Firms'), ['controller' => 'Firms', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Firm'), ['controller' => 'Firms', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="customerFiles form large-9 medium-8 columns content">
    <?= $this->Form->create($customerFile) ?>
    <fieldset>
        <legend><?= __('Add Customer File') ?></legend>
        <?php
            echo $this->Form->control('file_name');
            echo $this->Form->control('firm_id', ['options' => $firms]);
            echo $this->Form->control('tag');
            echo $this->Form->control('added_by');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
