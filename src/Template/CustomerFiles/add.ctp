<?php
/**
 * Form for adding a customer file.
 * 
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CustomerFile $customerFile
 */
?>
<div class="modal-header">
    <h4 class="modal-title font-weight-bold" id="modalLabel"><?= __('Nouveau document') ?></h4>
    <?= $this->Form->button('<span aria-hidden="true">&times;</span>', ['type' => 'button', 'class' => 'close', 'data-dismiss' => 'modal', 'aria-label' => 'Close', 'escape' => false]) ?>
</div>
<?= $this->Form->create($customerFiles, ['type' => 'file', 'class' => 'needs-validation', 'novalidate' => true]) ?>
<div class="modal-body"> 
    <div class="form-row d-flex justify-content-around align-items-center">
        <div class="col-md-5">
            <p>Société : <?= h($firm->name) ?></p>
            <?= $this->Form->hidden('firm_id', ['value' => $firm->id]) ?>
        </div>
        <div class="col-md-5 mb-3">
            <?= $this->Form->control('customer_directory_id', ['label' => ['text' => 'Dossier'], 'class' => 'form-control custom-select', 'type' => 'select', 'options' => $customerDirectories, 'empty' => 'Sélectionnez un dossier']) ?>
            <div class="invalid-feedback"></div>
        </div>
    </div>
    <div class="form-row d-flex justify-content-around align-items-center">
        <div class="col-md-5 mb-4 custom-file">
            <?= $this->Form->control('file_0', ['label' => ['text' => 'Sélectionnez un fichier', 'class' => 'custom-file-label'], 'type' => 'file', 'class' => 'form-control custom-file-input', 'maxlength' => 100]) ?>
            <div class="invalid-feedback"></div>
        </div>
        <div class="col-md-5 mb-4 custom-file">
            <?= $this->Form->control('file_1', ['label' => ['text' => 'Sélectionnez un fichier', 'class' => 'custom-file-label'], 'type' => 'file', 'class' => 'form-control custom-file-input', 'maxlength' => 100]) ?>
            <div class="invalid-feedback"></div>
        </div>
    </div>
    <div class="form-row d-flex justify-content-around align-items-center">
        <div class="col-md-5 mb-4 custom-file">
            <?= $this->Form->control('file_2', ['label' => ['text' => 'Sélectionnez un fichier', 'class' => 'custom-file-label'], 'type' => 'file', 'class' => 'form-control custom-file-input', 'maxlength' => 100]) ?>
            <div class="invalid-feedback"></div>
        </div>
        <div class="col-md-5 mb-4 custom-file">
            <?= $this->Form->control('file_3', ['label' => ['text' => 'Sélectionnez un fichier', 'class' => 'custom-file-label'], 'type' => 'file', 'class' => 'form-control custom-file-input', 'maxlength' => 100]) ?>
            <div class="invalid-feedback"></div>
        </div>
    </div>
    <div class="form-row d-flex justify-content-around align-items-center">
        <div class="col-md-5 mb-4 custom-file">
            <?= $this->Form->control('file_4', ['label' => ['text' => 'Sélectionnez un fichier', 'class' => 'custom-file-label'], 'type' => 'file', 'class' => 'form-control custom-file-input', 'maxlength' => 100]) ?>
            <div class="invalid-feedback"></div>
        </div>
        <div class="col-md-5 mb-4 custom-file">
            <?= $this->Form->control('file_5', ['label' => ['text' => 'Sélectionnez un fichier', 'class' => 'custom-file-label'], 'type' => 'file', 'class' => 'form-control custom-file-input', 'maxlength' => 100]) ?>
            <div class="invalid-feedback"></div>
        </div>
    </div>
    <div class="form-row d-flex justify-content-around align-items-center">
        <div class="col-md-5 mb-4 custom-file">
            <?= $this->Form->control('file_6', ['label' => ['text' => 'Sélectionnez un fichier', 'class' => 'custom-file-label'], 'type' => 'file', 'class' => 'form-control custom-file-input', 'maxlength' => 100]) ?>
            <div class="invalid-feedback"></div>
        </div>
        <div class="col-md-5 mb-4 custom-file">
            <?= $this->Form->control('file_7', ['label' => ['text' => 'Sélectionnez un fichier', 'class' => 'custom-file-label'], 'type' => 'file', 'class' => 'form-control custom-file-input', 'maxlength' => 100]) ?>
            <div class="invalid-feedback"></div>
        </div>
    </div>
    <div class="form-row d-flex justify-content-around align-items-center">
        <div class="col-md-5 mb-4 custom-file">
            <?= $this->Form->control('file_8', ['label' => ['text' => 'Sélectionnez un fichier', 'class' => 'custom-file-label'], 'type' => 'file', 'class' => 'form-control custom-file-input', 'maxlength' => 100]) ?>
            <div class="invalid-feedback"></div>
        </div>
        <div class="col-md-5 mb-4 custom-file">
            <?= $this->Form->control('file_9', ['label' => ['text' => 'Sélectionnez un fichier', 'class' => 'custom-file-label'], 'type' => 'file', 'class' => 'form-control custom-file-input', 'maxlength' => 100]) ?>
            <div class="invalid-feedback"></div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <?= $this->Form->button(__('Fermer'), ['type' => 'button', 'class' => 'btn dhl-custom-btn-2', 'data-dismiss' => 'modal']) ?>
    <?= $this->Form->button(__('Annuler'), ['type' => 'reset', 'class' => 'btn dhl-custom-btn-2']) ?>
    <?= $this->Form->button(__('Envoyer <i class="far fa-paper-plane"></i>'), ['escape' => false, 'type' => 'submit', 'class' => 'btn dhl-custom-btn']) ?>
</div>
<?= $this->Form->end() ?>
<script>
    $(function() {
        var form = $('#modal').find('form');
        var controls = $(form).find('.form-control:file');
        $(controls).change(function(e) {
            var label = $(this).prev();
            var divError = $(this).parent().next();
            if ($(this).prop('files').length) {
                $(label).text($(this).prop('files')[0].name);
                if ($(label).hasClass('is-invalid')) {
                    $(label).removeClass('is-invalid').addClass('is-valid');
                } else {
                    $(label).addClass('is-valid');
                }
                $(divError).css('display', 'none').empty();
            }
        }); 
        $(form).submit(function(e) {
            e.preventDefault();
            e.stopPropagation();
            var formData = new FormData(form[0]);
            if (validateControls($(controls))) {
                $.post({
                    url: $(form).prop('action'),
                    data: formData,
                    dataType: 'json',
                    contentType: false,
                    processData: false,
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', $(form).find('[name="_csrfToken"]').val());
                    },
                    success: function(resp) {
                        console.log('succès', resp);
                        if ($resp.items.length) {
                            // $.alert(resp.)
                        }
                    },
                    error: function(resp) {
                        console.log('erreur', resp);                        
                        // $('.modal-body').html(resp.responseText);
                    }
                });
            }
        });     
    });
    function validateControls(controls) {
        var isFile = false;
        $.each(controls, function(key, control) {
            if ($(control).prop('files').length) {
                isFile = true;
            }
        });
        if (!isFile) {
            var fileControl = $(controls)[0];
            var label = $(fileControl).prev();
            var divError = $(fileControl).parent().next();
            $(fileControl).attr('required', 'required');
            if (!$(label).hasClass('is-invalid')) {
                $(label).addClass('is-invalid');
            }
            $(divError).html('<small>' + $(fileControl).prop('validationMessage') + '</small>').css('display', 'block');
        }
        return isFile;
    }
</script>
