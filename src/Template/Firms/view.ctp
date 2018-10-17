<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Firm $firm
 */
?>
<div class="firms view large-9 medium-8 columns content">
    <h3><?= h($firm->name) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Name') ?></th>
            <td><?= h($firm->name) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($firm->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Workers Count') ?></th>
            <td><?= $this->Number->format($firm->workers_count) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Customer Files Count') ?></th>
            <td><?= $this->Number->format($firm->customer_files_count) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Added By') ?></th>
            <td><?= $this->Number->format($firm->added_by) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Created') ?></th>
            <td><?= h($firm->created) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Modified') ?></th>
            <td><?= h($firm->modified) ?></td>
        </tr>
    </table>
    <div class="related">
        <h4><?= __('Related Customer Files') ?></h4>
        <?php if (!empty($firm->customer_files)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('File Name') ?></th>
                <th scope="col"><?= __('Firm Id') ?></th>
                <th scope="col"><?= __('Tag') ?></th>
                <th scope="col"><?= __('Added By') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($firm->customer_files as $customerFiles): ?>
            <tr>
                <td><?= h($customerFiles->id) ?></td>
                <td><?= h($customerFiles->file_name) ?></td>
                <td><?= h($customerFiles->firm_id) ?></td>
                <td><?= h($customerFiles->tag) ?></td>
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
    <div class="related">
        <h4><?= __('Related Users') ?></h4>
        <?php if (!empty($firm->users)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('First Name') ?></th>
                <th scope="col"><?= __('Last Name') ?></th>
                <th scope="col"><?= __('Full Name') ?></th>
                <th scope="col"><?= __('Phone') ?></th>
                <th scope="col"><?= __('Email') ?></th>
                <th scope="col"><?= __('Password') ?></th>
                <th scope="col"><?= __('User Type Id') ?></th>
                <th scope="col"><?= __('Firm Id') ?></th>
                <th scope="col"><?= __('Created') ?></th>
                <th scope="col"><?= __('Modified') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($firm->users as $users): ?>
            <tr>
                <td><?= h($users->id) ?></td>
                <td><?= h($users->first_name) ?></td>
                <td><?= h($users->last_name) ?></td>
                <td><?= h($users->full_name) ?></td>
                <td><?= h($users->phone) ?></td>
                <td><?= h($users->email) ?></td>
                <td><?= h($users->password) ?></td>
                <td><?= h($users->user_type_id) ?></td>
                <td><?= h($users->firm_id) ?></td>
                <td><?= h($users->created) ?></td>
                <td><?= h($users->modified) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Users', 'action' => 'view', $users->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Users', 'action' => 'edit', $users->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Users', 'action' => 'delete', $users->id], ['confirm' => __('Are you sure you want to delete # {0}?', $users->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
