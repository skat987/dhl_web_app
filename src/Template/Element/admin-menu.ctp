<div class="navbar-nav mr-auto">
    <?= $this->Html->link('Accueil', ['_name' => 'adminHome'], ['class' => 'nav-item nav-link']) ?>
    <?= $this->Html->link('Utilisateurs', ['_name' => 'adminUsers'], ['class' => 'nav-item nav-link']) ?>
</div>
<span class="navbar-text">
    <?= $this->Html->link('Déconnexion', ['_name' => 'logout'], ['class' => 'nav-item nav-link']) ?>
</span>