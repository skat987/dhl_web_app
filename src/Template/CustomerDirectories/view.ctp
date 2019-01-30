<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CustomerDirectory $customerDirectory
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Customer Directory'), ['action' => 'edit', $customerDirectory->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Customer Directory'), ['action' => 'delete', $customerDirectory->id], ['confirm' => __('Are you sure you want to delete # {0}?', $customerDirectory->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Customer Directories'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Customer Directory'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Firms'), ['controller' => 'Firms', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Firm'), ['controller' => 'Firms', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Customer Files'), ['controller' => 'CustomerFiles', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Customer File'), ['controller' => 'CustomerFiles', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="customerDirectories view large-9 medium-8 columns content">
    <h3><?= h($customerDirectory->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($customerDirectory->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Firm') ?></th>
            <td><?= $customerDirectory->has('firm') ? $this->Html->link($customerDirectory->firm->name, ['controller' => 'Firms', 'action' => 'view', $customerDirectory->firm->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($customerDirectory->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Added By') ?></th>
            <td><?= $this->Number->format($customerDirectory->added_by) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($customerDirectory->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($customerDirectory->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Customer Files') ?></h4>
        <?php if (!empty($customerDirectory->customer_files)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Name') ?></th>
                <th scope="col"><?= __('Extension') ?></th>
                <th scope="col"><?= __('Key') ?></th>
                <th scope="col"><?= __('Firm Id') ?></th>
                <th scope="col"><?= __('Customer Directory Id') ?></th>
                <th scope="col"><?= __('Added By') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($customerDirectory->customer_files as $customerFiles): ?>
            <tr>
                <td><?= h($customerFiles->id) ?></td>
                <td><?= h($customerFiles->name) ?></td>
                <td><?= h($customerFiles->extension) ?></td>
                <td><?= h($customerFiles->key) ?></td>
                <td><?= h($customerFiles->firm_id) ?></td>
                <td><?= h($customerFiles->customer_directory_id) ?></td>
                <td><?= h($customerFiles->added_by) ?></td>
                <td><?= h($customerFiles->created) ?></td>
                <td><?= h($customerFiles->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'CustomerFiles', 'action' => 'view', $customerFiles->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'CustomerFiles', 'action' => 'edit', $customerFiles->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'CustomerFiles', 'action' => 'delete', $customerFiles->id], ['confirm' => __('Are you sure you want to delete # {0}?', $customerFiles->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
