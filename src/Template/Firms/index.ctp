<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Firm[]|\Cake\Collection\CollectionInterface $firms
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Firm'), ['action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Customer Files'), ['controller' => 'CustomerFiles', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New Customer File'), ['controller' => 'CustomerFiles', 'action' => 'add']) ?></li>
        <li><?= $this->Html->link(__('List Users'), ['controller' => 'Users', 'action' => 'index']) ?></li>
        <li><?= $this->Html->link(__('New User'), ['controller' => 'Users', 'action' => 'add']) ?></li>
    </ul>
</nav>
<div class="firms index large-9 medium-8 columns content">
    <h3><?= __('Firms') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('id') ?></th>
                <th scope="col"><?= $this->Paginator->sort('name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('workers_count') ?></th>
                <th scope="col"><?= $this->Paginator->sort('customer_files_count') ?></th>
                <th scope="col"><?= $this->Paginator->sort('added_by') ?></th>
                <th scope="col"><?= $this->Paginator->sort('created') ?></th>
                <th scope="col"><?= $this->Paginator->sort('modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($firms as $firm): ?>
            <tr>
                <td><?= $this->Number->format($firm->id) ?></td>
                <td><?= h($firm->name) ?></td>
                <td><?= $this->Number->format($firm->workers_count) ?></td>
                <td><?= $this->Number->format($firm->customer_files_count) ?></td>
                <td><?= $this->Number->format($firm->added_by) ?></td>
                <td><?= h($firm->created) ?></td>
                <td><?= h($firm->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['action' => 'view', $firm->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['action' => 'edit', $firm->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['action' => 'delete', $firm->id], ['confirm' => __('Are you sure you want to delete # {0}?', $firm->id)]) ?>
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
