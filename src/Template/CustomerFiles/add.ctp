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
<?= $this->Form->create($customerFile, ['type' => 'file']) ?>
<div class="modal-body"> 
    <div class="form-row">
        <div class="col-md-12 mb-3">
            <?= $this->Form->control('CustomerFile.Firm', [
                'label' => ['text' => 'Société'],
                'id' => 'firmsSelect',
                'class' => 'form-control-plaintext',
                'readonly' => true,
                'value' => $firm->name
            ]) ?>
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-12 mb-3 custom-file">
            <?= $this->Form->control('file', [
                'label' => ['text' => 'Sélectionnez un fichier', 'class' => 'custom-file-label'],
                'type' => 'file',
                'class' => 'form-control custom-file-input',
                'id' => 'fileSelected'
            ]) ?>
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-12 mb-3">
            <?= $this->Form->control('dir_name', [
                'label' => ['text' => 'Dossier'],
                'class' => 'form-control',
                'id' => 'dirsSelect',
                'type' => 'select',
                'options' => [
                    [
                        'value' => $firm->storage->read()[0],
                        'text' => $firm->storage->read()[0]
                    ]
                ],
                'empty' => 'Sélectionnez un dossier'
            ]) ?>
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
