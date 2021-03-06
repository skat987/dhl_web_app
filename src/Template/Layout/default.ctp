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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $this->fetch('title') ?>
    </title>      
    <?= $this->Html->css('bootstrap.min') ?>
    <?= $this->Html->css('jquery-ui.min') ?>
    <?= $this->Html->css('all') ?>
    <?= $this->Html->css('style') ?>
    <?= $this->Html->script('jquery-3.3.1.min') ?>
    <?= $this->Html->script('jquery-ui.min') ?>
    <?= $this->Html->script('alert', ['block' => 'scriptBottom']) ?>
    <?= $this->Html->script('all', ['block' => 'scriptBottom']) ?>
    <?= $this->Html->script('bootstrap.min', ['block' => 'scriptBottom']) ?>
    <?= $this->Html->script('popper.min', ['block' => 'scriptBottom']) ?>
    <?= $this->Html->script('main', ['block' => 'scriptBottom']) ?>

    <!-- Fetch meta, css and script -->
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
</head>
<body>
    <!-- Header -->
    <header class="container-fluid clearfix">
        <div class="row py-2 px-2">
            <div class="col-auto mx-0 px-0 my-0 py-0">
                <?= $this->Html->image('logo.png', [
                    'alt' => 'Logo',
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
                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userAccess" id="editMyAccessForm">
                                    <!-- Edit Access Form -->
                                </div>
                            </li>
                            <li class="border border-dark"></li>
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
        </div>
    </header>

    <!-- Flash component render -->
    <?= $this->Flash->render() ?>

    <!-- Content -->
    <div id="content" class="container-fluid clearfix">
        <?= $this->fetch('content') ?>
    </div>

    <!-- Footer -->
    <footer class="container-fluid clearfix">
        <div class="row py-2 px-2">
            <div class="col-12 d-flex justify-content-center">
                <div class="row align-items-center">
                    <div class="col-auto my-0 mx-0 py-0 px-0 d-flex flex-column">
                        <?= $this->Html->link(
                            $this->Html->image('iaora systems-logo-rvb 2018_300x100.jpg', [
                                'alt' => 'Logo Iaora Systems',
                                'style' => 'width: 115px',
                                'class' => 'rounded'
                            ]), 
                            $this->Url->build('http://www.iaora-systems.pf/'),
                            [
                                'escape' => false,
                                'target' => '_blank',
                                'title' => 'Visiter le site'
                            ]
                        ) ?>
                        <small id="IosLegend">Powered by Iaora systems</small>
                    </div>
                    <?php if (!empty($this->request->getSession()->read('Auth.User')) && ($this->request->getSession()->read('Auth.User.user_type_id') != 3)): ?>
                    <address class="col col-md-auto my-0 mx-0">
                        <small>Pour d'éventuels renseignements, veuillez contacter l'administrateur du site :</small>                        
                        <ul class="mb-0">
                            <li>
                                <small>
                                    <i class="far fa-envelope"></i> :                                
                                    <?= $this->Html->link(__(' courrier@ios.pf'),
                                        $this->Url->build('mailto:courrier@ios.pf')
                                    ) ?> 
                                </small>
                            </li>
                            <li>
                                <small><i class="fas fa-phone"></i><?= __(' : +(689) 40 54 26 60') ?></small>
                            </li>
                        </ul>
                    </address>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </footer>
    <?= $this->fetch('scriptBottom') ?>
</body>
</html>
