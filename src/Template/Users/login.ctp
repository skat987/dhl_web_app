<?php
/**
 * Login form.
 * 
 * @var \App\View\AppView $this
 */

$this->assign('title', 'Connexion');
?>
<?= $this->Flash->render('auth') ?>
<div class="row">
    <?= $this->Form->create(null, [
        'class' => 'col needs-validation',
        'novalidate' => true
    ]) ?>
    <div class="form-group">
        <?= $this->Form->control('email', [
            'type' => 'email',
            'label' => ['text' => 'Email'],
            'class' => 'form-control',
            'placeholder' => 'Entrer votre email',
            'required' => 'required'
        ]) ?>
        <div class="invalid-feedback"></div>
    </div>
    <div class="form-group">
        <?= $this->Form->control('password', [
            'type' => 'password',
            'minlength' => 8,
            'label' => ['text' => 'Mot de passe'],
            'class' => 'form-control',
            'placeholder' => 'Entrer votre mot de passe',
            'required' => 'required'
        ]) ?>
        <div class="invalid-feedback"></div>
    </div>
    <div class="col py-0 px-0">
        <p class="form-text text-muted py-0 px-0 my-0"><small>(<span id="helpRequired">*</span>) Champs obligatoires</small></p>
    </div>
    <?= $this->Form->submit(__('Connexion'), ['class' => 'btn btn-dark']) ?>
    <?= $this->Form->end() ?>
</div>
