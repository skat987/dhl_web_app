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
