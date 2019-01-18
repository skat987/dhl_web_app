<?php
/**
 * Form to add a new folder
 * 
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Firm $firm
 */
?>
<div class="modal-header">
    <h4 class="modal-title font-weight-bold"><?= __('Nouveau dossier') ?></h4>
    <?= $this->Form->button('<span aria-hidden="true">&times;</span>', [
        'type' => 'button',
        'class' => 'close',
        'data-dismiss' => 'modal',
        'aria-label' => 'Close',
        'escape' => false
    ]) ?>
</div>
<?= $this->Form->create(null, [
    'class' => 'needs-validation',
    'novalidate' => true
]) ?>
<div class="modal-body">
    <div class="form-group">
        <p>Société : <?= h($firm->name) ?></p>
    </div>
    <div class="form-group">
        <?= $this->Form->control('dirName', [
            'label' => [
                'text' => 'Nom du dossier'
            ],
            'type' => 'text',
            'class' => 'form-control',
            'placeholder' => 'Entrer le nom du dossier',
            'required' => 'required'
        ]) ?>
        <div class="invalid-feedback"></div>
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
