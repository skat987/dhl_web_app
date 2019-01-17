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
    <?= $this->Form->button('<span aria-hidden="true">&times;</span>', [
        'type' => 'button', 
        'class' => 'close', 
        'data-dismiss' => 'modal', 
        'aria-label' => 'Close', 
        'escape' => false
    ]) ?>
</div>
<?= $this->Form->create($customerFiles, [
    'type' => 'file',
    'class' => 'needs-validation',
    'novalidate' => true
]) ?>
<div class="modal-body"> 
    <div class="form-row">
        <div class="col-md-12">
            <p>Société : <?= h($firm->name) ?></p>
            <?= $this->Form->hidden('firm_id', [
                'value' => $firm->id
            ]) ?>
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-12 mb-3">
            <?= $this->Form->control('dir_name', [
                'label' => [
                    'text' => 'Dossier'
                ],
                'class' => 'form-control custom-select',
                'id' => 'dirsSelect',
                'type' => 'select',
                'options' => $firm->storage->read()[0],
                'empty' => 'Sélectionnez un dossier'
            ]) ?>
            <div class="invalid-feedback"></div>
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-12 mb-4 custom-file">
            <?= $this->Form->control('file_0', [
                'label' => [
                    'text' => 'Sélectionnez un fichier', 
                    'class' => 'custom-file-label'
                ],
                'type' => 'file',
                'class' => 'form-control custom-file-input'
            ]) ?>
            <div class="invalid-feedback"></div>
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-12 mb-4 custom-file">
            <?= $this->Form->control('file_1', [
                'label' => [
                    'text' => 'Sélectionnez un fichier', 
                    'class' => 'custom-file-label'
                ],
                'type' => 'file',
                'class' => 'form-control custom-file-input'
            ]) ?>
            <div class="invalid-feedback"></div>
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-12 mb-4 custom-file">
            <?= $this->Form->control('file_2', [
                'label' => [
                    'text' => 'Sélectionnez un fichier', 
                    'class' => 'custom-file-label'
                ],
                'type' => 'file',
                'class' => 'form-control custom-file-input'
            ]) ?>
            <div class="invalid-feedback"></div>
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-12 mb-4 custom-file">
            <?= $this->Form->control('file_3', [
                'label' => [
                    'text' => 'Sélectionnez un fichier', 
                    'class' => 'custom-file-label'
                ],
                'type' => 'file',
                'class' => 'form-control custom-file-input'
            ]) ?>
            <div class="invalid-feedback"></div>
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-12 mb-4 custom-file">
            <?= $this->Form->control('file_4', [
                'label' => [
                    'text' => 'Sélectionnez un fichier', 
                    'class' => 'custom-file-label'
                ],
                'type' => 'file',
                'class' => 'form-control custom-file-input'
            ]) ?>
            <div class="invalid-feedback"></div>
        </div>
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
