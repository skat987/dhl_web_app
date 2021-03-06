<?php
/**
 * CustomerDirectory Modification Form.
 * 
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CustomerDirectory $customerDirectory
 */
?>
<div class="modal-header">
    <h4 class="modal-title font-weight-bold" id="modalLabel"><?= __('Modifier un dossier') ?></h4>
    <?= $this->Form->button('<span aria-hidden="true">&times;</span>', ['type' => 'button', 'class' => 'close', 'data-dismiss' => 'modal', 'aria-label' => 'Close', 'escape' => false]) ?>
</div>
<?= $this->Form->create($customerDirectory, [
    'class' => 'needs-validation',
    'novalidate' => true
]) ?>
<div class="modal-body">
    <div class="form-group">
        <?= $this->Form->hidden('type', [
            'value' => substr($customerDirectory->name, 0, strpos($customerDirectory->name, '_'))
        ]) ?>
        <?= $this->Form->control('name', [
            'label' => ['text' => 'Nom du dossier'],
            'type' => 'text',
            'class' => 'form-control',
            'maxlength' => 60,
            'placeholder' => 'Entrer le nom du dossier',
            'value' => substr($customerDirectory->name, strpos($customerDirectory->name, '_') + 1, strlen($customerDirectory->name))
        ]) ?>
        <div class="invalid-feedback"></div>
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
        var name = $(form).find('[name="name"]');
        $(name).change(function() {
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
            var divError = $(name).parent().next();
            if (!$(name)[0].checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
                if ($(name).hasClass('is-valid')) {
                    $(name).removeClass('is-valid').addClass('is-invalid');
                } else {
                    $(name).addClass('is-invalid');
                }
                $(divError).html('<small>' + $(name).prop('validationMessage') + '</small>').css('display', 'block');
            } else {
                if ($(name).hasClass('is-invalid')) {
                    $(name).removeClass('is-invalid').addClass('is-valid');
                } else {
                    $(name).addClass('is-valid');
                }
                $(divError).css('display', 'none').empty();
            }
        });
    });
</script>