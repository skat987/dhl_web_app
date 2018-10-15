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

$appBaseTitle = 'DHL';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $appBaseTitle ?>:
        <?= $this->fetch('title') ?>
    </title>  
    <?= $this->Html->css('fa-all') ?>
    <?= $this->Html->css('bootstrap.min') ?>
    <?= $this->Html->script('jquery-3.3.1.min') ?>
    <?= $this->Html->script('bootstrap.min', ['block' => 'scriptBottom']) ?>
    <?= $this->Html->script('popper.min', ['block' => 'scriptBottom']) ?>
    <?= $this->Html->script('fa-all', ['block' => 'scriptBottom']) ?>

    <!-- Fetch meta, css and script -->
    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
</head>
<body>
    <!-- Header -->
    <header class="container-fluid">
        <div class="row">
            <div class="col">
                <h1 class="text-center"><?= $appBaseTitle ?>: <?= $this->fetch('title') ?></h1>
            </div>
        </div>
        <?php if (!empty($this->request->getSession()->read('Auth.User'))): ?>
        <div class="row">
            <div class="col">
                <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                    <span class="navbar-brand mb-0 h1">Menu</span>
                    <?= $this->Form->button('<span class="navbar-toggler-icon"></span>', [// TODO: method to show active menu item
                        'class' => 'navbar-toggler',
                        'type' => 'button', 
                        'data-toggle' => 'collapse',
                        'data-target' => '#navbarAdmin',
                        'aria-controls' => 'navbarAdmin',
                        'aria-expanded' => 'false',
                        'aria-label' => 'Toggle navigation',
                        'escape' => false
                    ]) ?>
                    <div class="collapse navbar-collapse" id="navbarAdmin">
                        <?php if ($this->request->getSession()->read('Auth.User.user_type_id') == 3): ?>
                        <?php echo $this->element('customer-menu') ?>
                        <?php else: ?>
                        <?php echo $this->element('admin-menu') ?>
                        <?php endif; ?>
                    </div>
                </nav>
            </div>
        </div>
        <?php endif; ?>
    </header>

    <!-- Flash component render -->
    <?= $this->Flash->render() ?>

    <!-- Content -->
    <div class="container clearfix">
        <?= $this->fetch('content') ?>
    </div>

    <!-- Footer -->
    <footer>
    </footer>
    <?= $this->fetch('scriptBottom') ?>
</body>
</html>
