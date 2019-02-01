<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CustomerFile $customerFile
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $customerFile->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $customerFile->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Customer Files'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Firms'), ['controller' => 'Firms', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Firm'), ['controller' => 'Firms', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Customer Directories'), ['controller' => 'CustomerDirectories', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Customer Directory'), ['controller' => 'CustomerDirectories', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="customerFiles form large-9 medium-8 columns content">
    <?= $this->Form->create($customerFile) ?>
    <fieldset>
        <legend><?= __('Edit Customer File') ?></legend>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('extension');
            echo $this->Form->control('key');
            echo $this->Form->control('firm_id', ['options' => $firms]);
            echo $this->Form->control('customer_directory_id', ['options' => $customerDirectories, 'empty' => true]);
            echo $this->Form->control('added_by');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
