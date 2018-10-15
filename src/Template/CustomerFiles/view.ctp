<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CustomerFile $customerFile
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Customer File'), ['action' => 'edit', $customerFile->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Customer File'), ['action' => 'delete', $customerFile->id], ['confirm' => __('Are you sure you want to delete # {0}?', $customerFile->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Customer Files'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Customer File'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Firms'), ['controller' => 'Firms', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Firm'), ['controller' => 'Firms', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="customerFiles view large-9 medium-8 columns content">
    <h3><?= h($customerFile->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('File Name') ?></th>
            <td><?= h($customerFile->file_name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Firm') ?></th>
            <td><?= $customerFile->has('firm') ? $this->Html->link($customerFile->firm->name, ['controller' => 'Firms', 'action' => 'view', $customerFile->firm->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($customerFile->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Tag') ?></th>
            <td><?= $this->Number->format($customerFile->tag) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Added By') ?></th>
            <td><?= $this->Number->format($customerFile->added_by) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($customerFile->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($customerFile->modified) ?></td>
        </tr>
    </table>
</div>
