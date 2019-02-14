<?php
/**
 * Form to add a new folder
 * 
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Firm $firm
 */
?>
<div class="modal-header">
    <h4 class="modal-title font-weight-bold"><?= __('Nouveau dossier {0}', mb_strtoupper($type)) ?></h4>
    <?= $this->Form->button('<span aria-hidden="true">&times;</span>', [
        'type' => 'button',
        'class' => 'close',
        'data-dismiss' => 'modal',
        'aria-label' => 'Close',
        'escape' => false
    ]) ?>
</div>
<?= $this->Form->create($customerDirectory, [
    'class' => 'needs-validation',
    'novalidate' => true
]) ?>
<div class="modal-body">
    <div class="form-group">
        <p>Société : <?= h($firm->name) ?></p>
        <?= $this->Form->hidden('firm_id', [
            'value' => $firm->id
        ]) ?>
        <?= $this->Form->hidden('type', [
            'value' => $type
        ]) ?>
    </div>
    <div class="form-group">
        <?= $this->Form->control('name', [
            'label' => [
                'text' => 'Nom du dossier'
            ],
            'type' => 'text',
            'class' => 'form-control',
            'placeholder' => 'Entrer le nom du dossier',
            'required' => true
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
    <?= $this->Form->button(__('Envoyer <i class="far fa-paper-plane"></i>'), [
        'escape' => false,
        'type' => 'submit',
        'class' => 'btn dhl-custom-btn'
    ]) ?>
</div>
<?= $this->Form->end() ?>