<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CustomerFile[]|\Cake\Collection\CollectionInterface $customerFiles
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Customer File'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Firms'), ['controller' => 'Firms', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Firm'), ['controller' => 'Firms', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Customer Directories'), ['controller' => 'CustomerDirectories', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Customer Directory'), ['controller' => 'CustomerDirectories', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="customerFiles index large-9 medium-8 columns content">
    <h3><?= __('Customer Files') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('extension') ?></th>
                <th scope="col"><?= $this->Paginator->sort('key') ?></th>
                <th scope="col"><?= $this->Paginator->sort('firm_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('customer_directory_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('added_by') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customerFiles as $customerFile): ?>
            <tr>
                <td><?= $this->Number->format($customerFile->id) ?></td>
                <td><?= h($customerFile->name) ?></td>
                <td><?= h($customerFile->extension) ?></td>
                <td><?= h($customerFile->key) ?></td>
                <td><?= $customerFile->has('firm') ? $this->Html->link($customerFile->firm->name, ['controller' => 'Firms', 'action' => 'view', $customerFile->firm->id]) : '' ?></td>
                <td><?= $customerFile->has('customer_directory') ? $this->Html->link($customerFile->customer_directory->name, ['controller' => 'CustomerDirectories', 'action' => 'view', $customerFile->customer_directory->id]) : '' ?></td>
                <td><?= $this->Number->format($customerFile->added_by) ?></td>
                <td><?= h($customerFile->created) ?></td>
                <td><?= h($customerFile->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $customerFile->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $customerFile->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $customerFile->id], ['confirm' => __('Are you sure you want to delete # {0}?', $customerFile->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
