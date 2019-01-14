<?php
/**
 * User Access Change Form
 * 
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<?= $this->Form->create($user, [
    'url' => [
        'controller' => 'Users',
        'action' => 'editMyAccess'
    ],
    'class' => 'px-4 py-3 needs-validation',
    'novalidate' => true
]) ?>
<div class="form-group">
    <?= $this->Form->control('email', [
        'label' => [
            'text' => 'Modifier mon email'
        ],
        'type' => 'email',
        'class' => 'form-control'
    ]) ?>
    <div class="invalid-feedback"></div>
</div>
<div class="form-group">
    <?= $this->Form->control('password', [
        'label' => [
            'text' => 'Modifier mon mot de passe'
        ],
        'type' => 'password',
        'class' => 'form-control'
    ]) ?>
    <div class="invalid-feedback"></div>
</div>
<?= $this->Form->button(__('Envoyer <i class="far fa-paper-plane"></i>'), [
    'escape' => false,
    'type' => 'submit',
    'class' => 'btn btn-dark',
    'confirm' => __('Voulez-vous vraiment valider ces accès ?')
]) ?>
<?= $this->Form->end() ?>