<?php
/**
 * Administrator menu template.
 */
?>
<div class="navbar-nav mr-auto">
    <?= $this->Html->link('Accueil', ['_name' => 'adminHome'], ['class' => 'nav-item nav-link']) ?>
    <?= $this->Html->link('Utilisateurs', ['_name' => 'adminUsers'], ['class' => 'nav-item nav-link']) ?>
</div>
<div class="dropdown" id="accessDropdown">
    <?= $this->Form->button(__('{0}', $this->request->getSession()->read('Auth.User.full_name')), [
        'type' => 'button',
        'class' => 'btn btn-secondary dropdown-toggle',
        'id' => 'userAccess',
        'title' => __('Modifier mes accès'),
        'data-toggle' => 'dropdown',
        'aria-haspopup' => 'true',
        'aria-expanded' => 'false',
        'data-link' => $this->Url->build(['_name' => 'editAccess'])
    ]) ?>
    <div class="dropdown-menu" aria-labelledby="userAccess" id="editMyAccessForm" style="width: 300px;">
        <!-- Content = Users.edit_my_access.ctp -->
    </div>
</div>
<span class="navbar-text">
    <?= $this->Html->link('Déconnexion', [
        '_name' => 'logout'
    ], [
        'class' => 'nav-item nav-link'
    ]) ?>
</span>