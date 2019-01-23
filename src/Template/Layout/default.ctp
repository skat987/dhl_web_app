<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

$appBaseTitle = 'DHL : ';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $appBaseTitle ?>
        <?= $this->fetch('title') ?>
    </title>  
    <?= $this->Html->css('bootstrap.min') ?>
    <?= $this->Html->css('jquery-ui.min') ?>
    <?= $this->Html->css('fa-all') ?>
    <?= $this->Html->css('style') ?>
    <?= $this->Html->script('jquery-3.3.1.min') ?>
    <?= $this->Html->script('jquery-ui.min') ?>
    <?= $this->Html->script('fa-all', ['block' => 'scriptBottom']) ?>
    <?= $this->Html->script('bootstrap.min', ['block' => 'scriptBottom']) ?>
    <?= $this->Html->script('popper.min', ['block' => 'scriptBottom']) ?>
    <?= $this->Html->script('main', ['block' => 'scriptBottom']) ?>

    <!-- Fetch meta, css and script -->
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
</head>
<body class="container-fluid">
    <!-- Header -->
    <header class="row">
        <div class="col-auto mx-0 px-0 my-0 py-0">
            <?= $this->Html->image('dhl_logo.gif', [
                'alt' => 'Logo DHL',
                'class' => 'mx-0 my-0'
            ]) ?>
        </div>
        <div class="col">
            <div class="row">
                <div class="col-12">
                    <h1 class="text-center"><?= __('Site d\'échanges sécurisés de documents') ?></h1>
                </div>
                <div class="col-12">
                    <h3 class="text-center"><?= $this->fetch('title') ?></h3>
                </div>
            </div>
        </div>
        <?php if (!empty($this->request->getSession()->read('Auth.User'))): ?>
        <?php if ($this->request->getSession()->read('Auth.User.user_type_id') == 3): ?>
        <div class="col-xl-3 px-0">
        <?php else: ?>
        <div class="col-xl-4 px-0">
        <?php endif; ?>
            <nav class="navbar navbar-expand-lg navbar-light px-0">
                <?= $this->Form->button('<span class="navbar-toggler-icon"></span>', ['class' => 'navbar-toggler', 'type' => 'button', 'data-toggle' => 'collapse', 'data-target' => '#navbar', 'aria-controls' => 'navbar', 'aria-expanded' => 'false', 'aria-label' => 'Toggle navigation', 'escape' => false]) ?>
                <div class="collapse navbar-collapse" id="navbar">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown" id="accessDropdown">
                            <?= $this->Html->link(h($this->request->getSession()->read('Auth.User.full_name')), '#', [                                    
                                'role' => 'button',
                                'class' => 'nav-link dropdown-toggle',
                                'id' => 'userAccess',
                                'title' => __('Modifier mes accès'),
                                'data-toggle' => 'dropdown',
                                'aria-haspopup' => 'true',
                                'aria-expanded' => 'false',
                                'data-link' => $this->Url->build(['_name' => 'editAccess'], true)
                            ]) ?>
                            <div class="dropdown-menu" aria-labelledby="userAccess" id="editMyAccessForm">
                                <!-- Edit Access Form -->
                            </div>
                        </li>
                        <li class="border border-secondary"></li>
                        <?php if (in_array($this->request->getSession()->read('Auth.User.user_type_id'), [1, 2])): ?>
                        <li class="nav-item">
                            <?= $this->Html->link(__('Sociétés'), [
                                '_name' => 'allFirms'
                            ], [
                                'class' => 'nav-link'
                            ]) ?>
                        </li>
                        <li class="nav-item">
                            <?= $this->Html->link(__('Utilisateurs'), [
                                '_name' => 'allUsers'
                            ], [
                                'class' => 'nav-link'
                            ]) ?>
                        </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <?= $this->Html->link(__('Déconnexion'), [
                                '_name' => 'logout'
                            ], [
                                'class' => 'nav-link'
                            ]) ?>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <?php endif; ?>
    </header>

    <!-- Flash component render -->
    <?= $this->Flash->render() ?>

    <!-- Content -->
    <div class="container-fluid clearfix">
        <?= $this->fetch('content') ?>
    </div>

    <!-- Footer -->
    <footer class="row">
        <div class="col d-flex justify-content-center">
            <?= $this->Html->image('iaora systems-logo-rvb 2018_300x100.jpg', [
                'alt' => 'Logo Iaora Systems',
                'style' => 'width: 100px'
            ]) ?>
        </div>
    </footer>
    <?= $this->fetch('scriptBottom') ?>
</body>
</html>
