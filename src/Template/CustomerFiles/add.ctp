<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CustomerFile $customerFile
 */
?>
<div class="modal-header">
    <h4 class="modal-title font-weight-bold" id="modalLabel"><?= __('Nouveau document') ?></h4>
    <?= $this->Form->button('<span aria-hidden="true">&times;</span>', ['type' => 'button', 'class' => 'close', 'data-dismiss' => 'modal', 'aria-label' => 'Close', 'escape' => false]) ?>
</div>
<?= $this->Form->create($customerFile, ['type' => 'file']) ?>
<div class="modal-body">
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
            <?= $this->Form->control('firm_id', [
                'label' => ['text' => 'Société'],
                'class' => 'form-control',
                'id' => 'firmsSelect',
                'options' => $firms,
                'empty' => 'Sélectionnez une société'
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
                'empty' => 'Selectionnez un dossier'
            ]) ?>
        </div>
    </div>
    <div class="form-row">
        <div class="col-md-6 mb-3">
            <?= $this->Form->control('newDir', [
                'class' => 'form-control',
                'id' => 'newDirName',
                'label' => ['text' => 'Nouveau dossier'],
                'placeholder' => 'Nom du nouveau dossier'
            ]) ?>
        </div>
        <div class="col-md-6 mb-3">
            <?= $this->Html->link('Ajouter dossier <i class="fas fa-plus-circle"></i>', '#', [
                'escape' => false,
                'class' => 'btn btn-outline-dark',
                'role' => 'button',
                'id' => 'newDirBtn',
                'data-link' => ''
            ]) ?>
            <small  class="form-text text-muted" id="newDirResult"></small >
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
