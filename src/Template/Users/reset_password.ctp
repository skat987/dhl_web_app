<?php
/**
 * User password reset form 
 */
?>
<div class="modal-header">
    <h4 class="modal-title font-weight-bold" id="modalLabel"><?= __('Ré-initialiser le mot de passe utilisateur') ?></h4>
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
        <div class="col mb-3">
            <p><?= h($user->full_name) ?></p>
        </div>
    </div>
    <div class="form-row">
        <div class="col mb-3">
            <?= $this->Form->control('newPassword', [
                'label' => [
                    'text' => 'Nouveau mot de passe'
                ],
                'type' => 'password',
                'class' => 'form-control',
                'required' => true,
                'minlength' => 8,
                'aria-describedby' => 'newPassHelpBlock'
            ]) ?>
            <div class="invalid-feedback"></div>
            <small id="newPassHelpBlock" class="form-text text-muted">
                <?= __('Au moins 8 caractères, 1 majuscule et 1 chiffre. Les caractères spéciaux sont exclus.') ?>
            </small>
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
        var form = $('#modal').find('form');
        var password = $(form).find('[name="newPassword"]');
        $(password).change(function() {
            var divError = $(this).parent().next();
            if ($(this)[0].checkValidity()) {
                if ($(this).hasClass('is-invalid')) {
                    $(this).removeClass('is-invalid').addClass('is-valid'); 
                } else {
                    $(this).addClass('is-valid');
                }
                $(divError).css('display', 'none').empty();
            }
        });
        $(form).submit(function(e) {
            var divError = $(password).parent().next();
            if (!$(password)[0].checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
                if ($(password).hasClass('is-valid')) {
                    $(password).removeClass('is-valid').addClass('is-invalid');
                } else {
                    $(password).addClass('is-invalid');
                }
                $(divError).html('<small>' + $(password).prop('validationMessage') + '</small>').css('display', 'block');
            } else {
                if ($(password).hasClass('is-invalid')) {
                    $(password).removeClass('is-invalid').addClass('is-valid');
                } else {
                    $(password).addClass('is-valid');
                }
                $(divError).css('display', 'none').empty();
            }
        });
    });
</script>