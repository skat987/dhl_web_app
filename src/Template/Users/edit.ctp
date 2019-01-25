<?php
/**
 * User modification form.
 * 
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="modal-header">
    <h4 class="modal-title font-weight-bold" id="modalLabel"><?= __('Modifier un utilisateur') ?></h4>
    <?= $this->Form->button('<span aria-hidden="true">&times;</span>', [
        'type' => 'button', 
        'class' => 'close', 
        'data-dismiss' => 'modal', 
        'aria-label' => 'Close', 
        'escape' => false
    ]) ?>
</div>
<?= $this->Form->create($user, [
    'class' => 'needs-validation',
    'novalidate' => true
]) ?>
<div class="modal-body">
    <div class="form-row">
        <div class="col-md-4 mb-3">
            <?= $this->Form->control('first_name', [
                'label' => ['text' => 'Prénom'],
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => 'Prénom',
                'required' => true
            ]) ?>
            <div class="invalid-feedback"></div>
        </div>
        <div class="col-md-4 mb-3">
            <?= $this->Form->control('last_name', [
                'label' => ['text' => 'Nom'],
                'type' => 'text',
                'class' => 'form-control',
                'placeholder' => 'Nom',
                'required' => true
            ]) ?>
            <div class="invalid-feedback"></div>
        </div>
        <div class="col-md-4 mb-3">
            <?= $this->Form->control('phone', [
                'label' => ['text' => 'Téléphone'],
                'type' => 'tel',
                'class' => 'form-control',
                'placeholder' => 'N° téléphone'
            ]) ?>
            <div class="invalid-feedback"></div>
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-6 mb-3">
            <?= $this->Form->control('email', [
                'label' => ['text' => 'Email'],
                'type' => 'email',
                'class' => 'form-control',
                'placeholder' => 'Adresse mail'
            ]) ?>
            <div class="invalid-feedback"></div>
        </div>
        <div class="col-md-6 mb-3">
            <?= $this->Form->control('password', [
                'label' => ['text' => 'Mot de passe'],
                'type' => 'password',
                'minlength' => 8,
                'class' => 'form-control',
                'aria-describedby' => 'passwordHelpBlock',
                'placeholder' => 'Mot de passe'
            ]) ?>
            <div class="invalid-feedback"></div>
            <small id="passwordHelpBlock" class="form-text text-muted"><?= __('Au moins 8 caractères') ?></small>
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-6 mb-3">
            <?= $this->Form->control('user_type_id', [
                'label' => ['text' => 'Type d\'utilisateur'],
                'class' => 'form-control custom-select',
                'options' => $userTypes,
                'empty' => 'Sélectionnez un type'
            ]) ?>
            <div class="invalid-feedback"></div>
        </div>
        <div class="col-md-6 mb-3">
            <?= $this->Form->control('firm_id', [
                'label' => ['text' => 'Société'],
                'class' => 'form-control custom-select',
                'options' => $firms,
                'empty' => 'Selectionnez une société'
            ]) ?>
            <div class="invalid-feedback"></div>
        </div>
    </div>    
    <div class="col py-0 px-0">
        <p class="form-text text-muted py-0 px-0 my-0"><small>(<span id="helpRequired">*</span>) Champs obligatoires</small></p>
    </div>
</div>
<div class="modal-footer">
    <?= $this->Form->button(__('Annuler'), [
        'type' => 'button',
        'class' => 'btn btn-secondary',
        'data-dismiss' => 'modal'
    ]) ?>
    <?= $this->Form->button(__('Envoyer <i class="far fa-paper-plane"></i>'), [
        'escape' => false,
        'type' => 'submit',
        'class' => 'btn btn-dark'
    ]) ?>
</div>
<?= $this->Form->end() ?>
