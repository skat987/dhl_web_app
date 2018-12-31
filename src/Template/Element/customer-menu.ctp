<?php
/**
 * Customer menu template.
 */
?>
<div class="navbar-nav mr-auto">
    <span class="navbar-text"><?= __('Espace client: ') . h($firm->name) ?></span>
</div>
<span class="navbar-text">
    <?= $this->Html->link('Déconnexion', ['_name' => 'logout'], ['class' => 'nav-item nav-link']) ?>
</span>