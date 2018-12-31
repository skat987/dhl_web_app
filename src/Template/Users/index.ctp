<?php
/**
 * Users list.
 * 
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User[]|\Cake\Collection\CollectionInterface $users
 */

$this->assign('title', 'Liste des utilisateurs');
// Call the Modal element
echo $this->element('modal');
?>
<div class="row">
    <div class="col">
        <?= $this->Html->link(__('Ajouter un utilisateur <i class="fas fa-plus-circle"></i>'), '#', [
            'escape' => false,
            'data-toggle' => 'modal',
            'data-target' => '#modal',
            'data-link' => $this->Url->build(['_name' => 'userAdd']),
            'role' => 'button',
            'class' => 'btn btn-outline-dark'
        ]) ?>
    </div>
</div>
<div class="table-responsive">
    <table class="table">
        <caption><?= __('Liste des utilisateurs') ?></caption>
        <thead class="thead-light">
            <tr>
                <th scope="col"><?= $this->Paginator->sort('full_name', 'Nom') ?></th>
                <th scope="col"><?= $this->Paginator->sort('phone', 'Téléphone') ?></th>
                <th scope="col"><?= $this->Paginator->sort('email', 'Email') ?></th>
                <th scope="col"><?= $this->Paginator->sort('user_type_id', 'type d\'utilisateur') ?></th>
                <th scope="col"><?= $this->Paginator->sort('firm_id', 'Société') ?></th>
                <th scope="col"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
            <tr>
                <td><?= h($user->full_name) ?></td>
                <td><?= h($user->phone) ?></td>
                <td><?= h($user->email) ?></td>
                <td><?= $user->has('user_type') ? h($user->user_type->name) : '' ?></td>
                <td><?= $user->has('firm') ? $this->Html->link($user->firm->name, ['_name' => 'firmView', $user->firm->id]) : '' ?></td>
                <td>
                    <?= $this->Html->link('<i class="far fa-eye"></i>', '#', [
                        'escape' => false,
                        'data-toggle' => 'modal',
                        'data-target' => '#modal',
                        'data-link' => $this->Url->build(['_name' => 'userView', $user->id]),
                        'data-id' => $user->id
                    ]) ?>
                    <?= $this->Html->link('<i class="far fa-edit"></i>', '#', [
                        'escape' => false,
                        'data-toggle' => 'modal',
                        'data-target' => '#modal',
                        'data-link' => $this->Url->build(['_name' => 'userEdit', $user->id]),
                        'data-id' => $user->id
                    ]) ?>                    
                    <?= $this->Form->postLink('<i class="far fa-trash-alt"></i>', [
                        '_name' => 'userDelete', $user->id
                    ], [
                        'escape' => false,
                        'confirm' => __('Voulez-vous vraiment supprimer l\'utilisateur: {0}?', $user->full_name)
                    ]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<nav aria-label="user list pagination">
    <ul class="pagination justify-content-center">
        <?= $this->Paginator->first('<< ' . __('Premier')) ?>
        <?= $this->Paginator->prev('< ' . __('Précédent')) ?>
        <?= $this->Paginator->numbers() ?>
        <?= $this->Paginator->next(__('Suivant') . ' >') ?>
        <?= $this->Paginator->last(__('Dernier') . ' >>') ?>
    </ul>
    <p><?= $this->Paginator->counter(['format' => __('Page {{page}} sur {{pages}}, {{current}} enregistrement(s) affichés sur {{count}}')]) ?></p>
</nav>
