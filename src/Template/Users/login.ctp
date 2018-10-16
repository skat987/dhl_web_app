<?php
/**
 * @var \App\View\AppView $this
 */
?>
<?= $this->Flash->render('auth') ?>
<div class="row">
    <div class="col">
        <h3 class="text-center"><?= __('Se connecter') ?></h3>
    </div>
</div>
<div class="row">
    <div class="col">
        <?= $this->Form->create() ?>
        <div class="form-group">
            <?= $this->Form->control('email', [
                'type' => 'email',
                'label' => ['text' => 'Email'],
                'class' => 'form-control',
                'placeholder' => 'Entrer votre email'
            ]) ?>
        </div>
        <div class="form-group">
            <?= $this->Form->control('password', [
                'type' => 'password',
                'label' => ['text' => 'Mot de passe'],
                'class' => 'form-control',
                'placeholder' => 'Entrer votre mot de passe'
            ]) ?>
        </div>
        <?= $this->Form->submit(__('Connexion'), ['class' => 'btn btn-dark']) ?>
        <?= $this->Form->end() ?>
    </div>
</div>
