<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Firm $firm
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('List Firms'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Customer Files'), ['controller' => 'CustomerFiles', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Customer File'), ['controller' => 'CustomerFiles', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="firms form large-9 medium-8 columns content">
    <?= $this->Form->create($firm) ?>
    <fieldset>
        <legend><?= __('Add Firm') ?></legend>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('workers_count');
            echo $this->Form->control('customer_files_count');
            echo $this->Form->control('added_by');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
