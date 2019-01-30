<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CustomerDirectory[]|\Cake\Collection\CollectionInterface $customerDirectories
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Customer Directory'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Firms'), ['controller' => 'Firms', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Firm'), ['controller' => 'Firms', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Customer Files'), ['controller' => 'CustomerFiles', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Customer File'), ['controller' => 'CustomerFiles', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="customerDirectories index large-9 medium-8 columns content">
    <h3><?= __('Customer Directories') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('firm_id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('added_by') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($customerDirectories as $customerDirectory): ?>
            <tr>
                <td><?= $this->Number->format($customerDirectory->id) ?></td>
                <td><?= h($customerDirectory->name) ?></td>
                <td><?= $customerDirectory->has('firm') ? $this->Html->link($customerDirectory->firm->name, ['controller' => 'Firms', 'action' => 'view', $customerDirectory->firm->id]) : '' ?></td>
                <td><?= $this->Number->format($customerDirectory->added_by) ?></td>
                <td><?= h($customerDirectory->created) ?></td>
                <td><?= h($customerDirectory->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $customerDirectory->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $customerDirectory->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $customerDirectory->id], ['confirm' => __('Are you sure you want to delete # {0}?', $customerDirectory->id)]) ?>
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
