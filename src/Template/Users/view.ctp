<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="modal-header">
    <h4 class="modal-title font-weight-bold" id="modalLabel"><?= __('Profil utilisateur') ?></h4>
    <?= $this->Form->button('<span aria-hidden="true">&times;</span>', ['type' => 'button', 'class' => 'close', 'data-dismiss' => 'modal', 'aria-label' => 'Close', 'escape' => false]) ?>
</div>
<?= $this->Form->create($user) ?>
<div class="modal-body">
    <div class="form-row">
        <div class="col-md-4 mb-3">
            <?= $this->Form->control('first_name', [
                'label' => ['text' => 'Prénom', 'class' => 'font-weight-bold'],
                'type' => 'text',
                'class' => 'form-control-plaintext',
                'readonly' => true
            ]) ?>
        </div>
        <div class="col-md-4 mb-3">
            <?= $this->Form->control('last_name', [
                'label' => ['text' => 'Nom', 'class' => 'font-weight-bold'],
                'type' => 'text',
                'class' => 'form-control-plaintext',
                'readonly' => true
            ]) ?>
        </div>
        <div class="col-md-4 mb-3">
            <?= $this->Form->control('phone', [
                'label' => ['text' => 'Téléphone', 'class' => 'font-weight-bold'],
                'type' => 'tel',
                'class' => 'form-control-plaintext',
                'readonly' => true
            ]) ?>
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-6 mb-3">
            <?= $this->Form->control('email', [
                'label' => ['text' => 'Email', 'class' => 'font-weight-bold'],
                'type' => 'email',
                'class' => 'form-control-plaintext',
                'readonly' => true
            ]) ?>
        </div>
        <div class="col-md-6 mb-3">
            <?= $this->Form->control('password', [
                'label' => ['text' => 'Mot de passe', 'class' => 'font-weight-bold'],
                'type' => 'password',
                'class' => 'form-control-plaintext',
                'readonly' => true
            ]) ?>
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-6 mb-3">
            <?= $this->Form->control('user_type_id', [
                'label' => ['text' => 'Type d\'utilisateur', 'class' => 'font-weight-bold'],
                'type' => 'text',
                'class' => 'form-control-plaintext',
                'value' => $user->user_type->name,
                'readonly' => true
            ]) ?>
        </div>
        <div class="col-md-6 mb-3">
            <?= $this->Form->control('firm_id', [
                'label' => ['text' => 'Société', 'class' => 'font-weight-bold'],
                'type' => 'text',
                'class' => 'form-control-plaintext',
                'value' => $user->firm->name,
                'readonly' => true
            ]) ?>
        </div>
    </div>
</div>
<div class="modal-footer">
    <?= $this->Form->button(__('Retour'), [
        'type' => 'button',
        'class' => 'btn btn-secondary',
        'data-dismiss' => 'modal'
    ]) ?>
</div>
<?= $this->Form->end() ?>
