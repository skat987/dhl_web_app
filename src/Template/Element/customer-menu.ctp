<?php
/**
 * Customer menu template.
 */
?>
<div class="navbar-nav mr-auto">
    <span class="navbar-text"><?= __('Espace client : {0}', h($firm->name)) ?></span>
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
    <?= $this->Html->link('Déconnexion', ['_name' => 'logout'], ['class' => 'nav-item nav-link']) ?>
</span>