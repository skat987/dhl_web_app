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
<section class="row">
    <div class="col">
        <?= $this->Html->link(__('<i class="fas fa-user"></i> Nouvel utilisateur <i class="fas fa-plus-circle"></i>'), '#', [
            'escape' => false,
            'data-toggle' => 'modal',
            'data-target' => '#modal',
            'data-link' => $this->Url->build(['_name' => 'addUser'], true),
            'role' => 'button',
            'class' => 'dhl-custom-btn btn btn-block mt-3 mb-3',
            'title' => __('Ajouter un utilisateur')
        ]) ?>
    </div>
</section>
<section class="table-responsive">
    <table class="table">
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
                <td>
                    <?= $user->has('firm') ? $this->Html->link($user->firm->name, [
                        '_name' => 'viewFirm', 
                        $user->firm->id
                    ], [
                        'title' => __('Accéder à l\'espace client')
                    ]) : '' ?></td>
                <td>
                    <?= $this->Html->link('<i class="far fa-eye"></i>', '#', [
                        'escape' => false,
                        'title' => __('Voir le profil'),
                        'data-toggle' => 'modal',
                        'data-target' => '#modal',
                        'data-link' => $this->Url->build(['_name' => 'viewUser', $user->id], true)
                    ]) ?>
                    <?= $this->Html->link('<i class="far fa-edit"></i>', '#', [
                        'escape' => false,
                        'title' => __('Modifier le profil'),
                        'data-toggle' => 'modal',
                        'data-target' => '#modal',
                        'data-link' => $this->Url->build(['_name' => 'editUser', $user->id], true)
                    ]) ?>                    
                    <?= $this->Form->postLink('<i class="far fa-trash-alt"></i>', [
                        '_name' => 'deleteUser', 
                        $user->id
                    ], [
                        'escape' => false,
                        'title' => __('Supprimer l\'utilisateur'),
                        'confirm' => __('Voulez-vous vraiment supprimer l\'utilisateur {0}?', $user->full_name)
                    ]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>
<section>
    <nav aria-label="users list pagination">
        <ul class="pagination justify-content-center">
            <?= $this->Paginator->first('<< ' . __('Premier')) ?>
            <?= $this->Paginator->prev('< ' . __('Précédent')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('Suivant') . ' >') ?>
            <?= $this->Paginator->last(__('Dernier') . ' >>') ?>
        </ul>
    </nav>
</section>
