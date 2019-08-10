<?php
/**
 * Display the action panel
 */
?>
<section>
    <div class="row">
        <div class="col mb-3 d-flex justify-content-center">
            <?= $this->Form->create(null, [
                'id' => __('searchForm_{0}', $firmKey),
                'class' => 'form-inline'
            ]) ?>
            <?= $this->Form->search('customer_directory_name', [
                'type' => 'search',
                'class' => 'form-control mr-sm-2',
                'placeholder' => __('Rechercher un dossier'),
                'aria-label' => __('Rechercher un dossier dans la liste'),
                'autocomplete' => 'off',
                'data-firm' => $firm->id,
                'id' => __('searchDirectory_{0}', $firmKey),
                'list' => __('optionsDirectories_{0}', $firmKey)
            ]) ?>
            <datalist id=<?= __('optionsDirectories_{0}', $firmKey) ?>></datalist>
            <?= $this->Form->button(__('<i class="fas fa-search"></i> Rechercher'), [
                'type' => 'submit',
                'class' => 'btn dhl-custom-btn my-2 my-sm-0 mr-sm-2',
                'escape' => false
            ]) ?>
            <?= $this->Form->button(__('Tous les dossiers'), [
                'id' => __('resetSearch_{0}', $firmKey),
                'type' => 'reset',
                'class' => 'btn dhl-custom-btn my-2 my-sm-0 mr-sm-2',
                'data-link' => $this->Url->build(['_name' => 'getStorage', $firm->id, 'all'], true)
            ]) ?>
            <?= $this->Form->button(__('DGF'), [
                'id' => __('dgf_{0}', $firmKey),
                'type' => 'reset',
                'class' => 'btn dhl-custom-btn my-2 my-sm-0 mr-sm-2',
                'data-link' => $this->Url->build(['_name' => 'getStorage', $firm->id, 'dgf'], true)
            ]) ?>
            <?= $this->Form->button(__('EXPRESS'), [
                'id' => __('express_{0}', $firmKey),
                'type' => 'reset',
                'class' => 'btn dhl-custom-btn my-2 my-sm-0',
                'data-link' => $this->Url->build(['_name' => 'getStorage', $firm->id, 'express'], true)
            ]) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
    <?php if ($this->request->getSession()->read('Auth.User.user_type_id') != 3): ?>
    <div class="row">
        <div class="col-md-4 mb-3 d-flex justify-content-center">
            <?= $this->Html->link(__('<i class="fas fa-file"></i> Nouveau document <i class="fas fa-plus-circle"></i>'), '#', [
                'escape' => false,
                'role' => 'button',
                'class' => 'btn dhl-custom-btn add-customer-file-link',
                'title' => __('Ajouter un document à la société {0}', $firm->name),
                'data-toggle' => 'modal',
                'data-target' => '#modal',
                'data-link' => $this->Url->build(['_name' => 'addCustomerFile', $firm->id], true)
            ]) ?>
        </div>
        <div class="col-md-4 mb-3 d-flex justify-content-center">
            <?= $this->Html->link(__('<i class="fas fa-folder"></i> Nouveau dossier DGF <i class="fas fa-plus-circle"></i>'), '#', [
                'escape' => false,
                'role' => 'button',
                'class' => 'btn dhl-custom-btn',
                'title' => __('Ajouter un dossier à la société {0}', $firm->name),
                'data-toggle' => 'modal',
                'data-target' => '#modal',
                'data-link' => $this->Url->build(['_name' => 'addDirectory', $firm->id, 'dgf'], true)
            ]) ?>
        </div>
        <div class="col-md-4 mb-3 d-flex justify-content-center">
            <?= $this->Html->link(__('<i class="fas fa-folder"></i> Nouveau dossier EXPRESS <i class="fas fa-plus-circle"></i>'), '#', [
                'escape' => false,
                'role' => 'button',
                'class' => 'btn dhl-custom-btn',
                'title' => __('Ajouter un dossier à la société {0}', $firm->name),
                'data-toggle' => 'modal',
                'data-target' => '#modal',
                'data-link' => $this->Url->build(['_name' => 'addDirectory', $firm->id, 'express'], true)
            ]) ?>
        </div>
    </div>
    <?php endif; ?>
</section>
