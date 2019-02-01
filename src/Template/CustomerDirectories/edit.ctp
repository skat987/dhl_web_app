<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CustomerDirectory $customerDirectory
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $customerDirectory->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $customerDirectory->id)]
            )
        ?></li>
        <li><?= $this->Html->link(__('List Customer Directories'), ['action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('List Firms'), ['controller' => 'Firms', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Firm'), ['controller' => 'Firms', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Customer Files'), ['controller' => 'CustomerFiles', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Customer File'), ['controller' => 'CustomerFiles', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="customerDirectories form large-9 medium-8 columns content">
    <?= $this->Form->create($customerDirectory) ?>
    <fieldset>
        <legend><?= __('Edit Customer Directory') ?></legend>
        <?php
            echo $this->Form->control('name');
            echo $this->Form->control('firm_id', ['options' => $firms]);
            echo $this->Form->control('added_by');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
