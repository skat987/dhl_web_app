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
    </ul>
</nav>
<div class="customerFiles form large-9 medium-8 columns content">
    <?= $this->Form->create($customerFile, ['type' => 'file']) ?>
    <fieldset>
        <legend><?= __('Edit Customer File') ?></legend>
        <?php            
            echo $this->Form->control('file_name');
            echo $this->Form->control('file', ['type' => 'file', 'defaultValue' => $customerFile->file]);
            echo $this->Form->control('firm_id', ['options' => $firms, 'id' => 'firmsList', 'empty' => 'Sélectionner une société']);
            echo $this->Form->control('dir_name', ['type' => 'select', 'id' => 'dirsList', 'empty' => 'Sélectionner un dossier']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Submit')) ?>
    <?= $this->Form->end() ?>
</div>
