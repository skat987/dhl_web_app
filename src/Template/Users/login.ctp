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
        'class' => 'needs-validation',
        'novalidate' => true,
        'id' => 'form-login'
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
    <?= $this->Form->button(__('Valider <i class="fas fa-check"></i>'), [
        'type' => 'submit',
        'class' => 'btn btn-block dhl-custom-btn',
        'escape' => false
    ]) ?>
    <?= $this->Form->end() ?>
</div>
<script>
    $(function() {
        var form = $('form');
        var email = $(form).find('[name="email"]');
        var password = $(form).find('[name="password"]');
        $(email).change(function() {
            var divError = $(this).parent().next();
            if (isEmailValid($(this)[0])) {
                if ($(this).hasClass('is-invalid')) {
                    $(this).removeClass('is-invalid').addClass('is-valid'); 
                } else {
                    $(this).addClass('is-valid');
                }
                $(divError).css('display', 'none').empty();
            }
        });
        $(password).change(function() {
            var divError = $(this).parent().next();
            if (isPasswordValid($(this)[0])) {
                if ($(this).hasClass('is-invalid')) {
                    $(this).removeClass('is-invalid').addClass('is-valid'); 
                } else {
                    $(this).addClass('is-valid');
                }
                $(divError).css('display', 'none').empty();
            }
        });
        $(form).submit(function(e) {
            if (!isControlsValid($(email), $(password))) {
                e.preventDefault();
                e.stopPropagation();
            }
        });
    });
    function isEmailValid(input) {
        const _EMAIL_PATTERN = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return (input.checkValidity() && _EMAIL_PATTERN.test($(input).val()));
    }
    function isPasswordValid(input) {
        const _PASSWORD_PATTERN = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$/;
        return (input.checkValidity() && _PASSWORD_PATTERN.test($(input).val()));
    }
    function isControlsValid(email, password) {
        var errorEmail = $(email).parent().next();
        var errorPassword = $(password).parent().next();
        var isValid = true;
        if (isEmailValid($(email)[0])) {
            if ($(email).hasClass('is-invalid')) {
                $(email).removeClass('is-invalid').addClass('is-valid'); 
            } else {
                $(email).addClass('is-valid');
            }
            $(errorEmail).css('display', 'none').empty();
        } else {
            if (isValid) {
                isValid = false;
            }
            if ($(email).hasClass('is-valid')) {
                $(email).removeClass('is-valid').addClass('is-invalid');
            } else {
                $(email).addClass('is-invalid');
            }
            $(errorEmail).html('<small>' + $(email).prop('validationMessage') + '</small>').css('display', 'block');
        }
        if (isPasswordValid($(password)[0])) {
            if ($(password).hasClass('is-invalid')) {
                $(password).removeClass('is-invalid').addClass('is-valid'); 
            } else {
                $(password).addClass('is-valid');
            }
            $(errorPassword).css('display', 'none').empty();
        } else {
            if (isValid) {
                isValid = false;
            }
            if ($(password).hasClass('is-valid')) {
                $(password).removeClass('is-valid').addClass('is-invalid');
            } else {
                $(password).addClass('is-invalid');
            }
            $(errorPassword).html('<small>' + $(password).prop('validationMessage') + '</small>').css('display', 'block');
        }
        return isValid;
    }
</script>
