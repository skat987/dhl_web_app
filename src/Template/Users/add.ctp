<?php
/**
 * Form to add a user.
 * 
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\User $user
 */
?>
<div class="modal-header">
    <h4 class="modal-title font-weight-bold" id="modalLabel"><?= __('Ajouter un utilisateur') ?></h4>
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
            <small id="passwordHelpBlock" class="form-text text-muted">
                <?= __('Au moins 8 caractères, 1 majuscule et 1 chiffre. Les caractères spéciaux sont exclus.') ?>
            </small>
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
                'empty' => 'Sélectionnez une société'
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
        'class' => 'btn dhl-custom-btn-2',
        'data-dismiss' => 'modal'
    ]) ?>
    <?= $this->Form->button(__('Valider <i class="fas fa-check"></i>'), [
        'escape' => false,
        'type' => 'submit',
        'class' => 'btn dhl-custom-btn'
    ]) ?>
</div>
<?= $this->Form->end() ?>
<script>
    $(function() {
        const _EMAIL_PATTERN = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        const _PASSWORD_PATTERN = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$/;
        var form = $('#modal').find('form');
        var controls = $(form).find('.form-control');
        $(controls).change(function() {
            var checks;
            var divError = $(this).parent().next();
            if ($(this).prop('type') === 'email') {
                checks = ($(this)[0].checkValidity() && _EMAIL_PATTERN.test($(this).val()));
            } else if ($(this).prop('type') === 'password') {
                checks = ($(this)[0].checkValidity() && _PASSWORD_PATTERN.test($(this).val()));
            } else {
                checks = $(this)[0].checkValidity();
            }
            if (checks) {
                if ($(this).hasClass('is-invalid')) {
                    $(this).removeClass('is-invalid').addClass('is-valid'); 
                } else {
                    $(this).addClass('is-valid');
                }
                $(divError).css('display', 'none').empty();
            }
        });
        $(form).submit(function(e) {
            if (!validateControls($(controls))) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    });
    function validateControls(controls) {        
        const _EMAIL_PATTERN = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        const _PASSWORD_PATTERN = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$/;
        var checks, divError;
        var isValid = true;
        $.each(controls, function(key, control) {
            divError = $(control).parent().next();
            if ($(control).prop('type') === 'email') {
                checks = ($(control)[0].checkValidity() && _EMAIL_PATTERN.test($(control).val()));
            } else if ($(control).prop('type') === 'password') {
                checks = ($(control)[0].checkValidity() && _PASSWORD_PATTERN.test($(control).val()));
            } else {
                checks = $(control)[0].checkValidity();
            }
            if (checks) {
                if ($(control).hasClass('is-invalid')) {
                    $(control).removeClass('is-invalid').addClass('is-valid'); 
                } else {
                    $(control).addClass('is-valid');
                }
                $(divError).css('display', 'none').empty();
            } else {
                if (isValid) {
                    isValid = false;
                }
                if ($(control).hasClass('is-valid')) {
                    $(control).removeClass('is-valid').addClass('is-invalid');
                } else {
                    $(control).addClass('is-invalid');
                }
                $(divError).html('<small>' + $(control).prop('validationMessage') + '</small>').css('display', 'block');
            }
        });
        return isValid;
    }
</script>