<?php
/**
 * Display the storage content of a firm
 * 
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CustomerFile[]|\Cake\Collection\CollectionInterface $customerFiles
 */
?>
<?php if ($this->request->getSession()->read('Auth.User.user_type_id') != 3): ?>
<section>
    <div class="row">
        <div class="col d-flex justify-content-center">
            <?= $this->Form->create(null, [
                'class' => 'form-inline'
            ]) ?>
            <?= $this->Form->search('search', [
                'type' => 'search',
                'class' => 'form-control mr-sm-2',
                'placeholder' => __('Rechercher un dossier'),
                'aria-label' => 'Search'
            ]) ?>
            <?= $this->Form->button(__('Rechercher <i class="fas fa-search"></i>'), [
                'type' => 'submit',
                'class' => 'btn btn-outline-success my-2 my-sm-0',
                'escape' => false
            ]) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 mb-3 d-flex justify-content-center">
            <?= $this->Html->link(__('<i class="fas fa-file"></i> Nouveau document <i class="fas fa-plus-circle"></i>'), '#', [
                'escape' => false,
                'role' => 'button',
                'class' => 'btn dhl-custom-btn',
                'title' => __('Ajouter un document à la société {0}', $firm->name),
                'data-toggle' => 'modal',
                'data-target' => '#modal',
                'data-link' => $this->Url->build(['_name' => 'addCustomerFile', $firm->id], true)
            ]) ?>
        </div>
        <div class="col-md-6 mb-3 d-flex justify-content-center">
            <?= $this->Html->link(__('<i class="fas fa-folder"></i> Nouveau dossier <i class="fas fa-plus-circle"></i>'), '#', [
                'escape' => false,
                'role' => 'button',
                'class' => 'btn dhl-custom-btn',
                'title' => __('Ajouter un dossier à la société {0}', $firm->name),
                'data-toggle' => 'modal',
                'data-target' => '#modal',
                'data-link' => $this->Url->build(['_name' => 'addDirectory', $firm->id], true)
            ]) ?>
        </div>
    </div>
</section>
<?php endif; ?>
<div class="container-fluid clearfix">    
    <?php if ($firm->customer_directories_count > 0): ?>
    <section class="accordion" id=<?= __('storage_firm_{0}', $firm->id) ?>>
        <?php foreach ($customerDirectories as $key => $customerDirectory): ?>
        <div class="card">
            <div class="card-header" id=<?= __('firm_{0}_dir_{1}_heading', [$firm->id, $key]) ?>>
                <h5 class="mb-0">
                    <?= $this->Form->button(__('<i class="fas fa-folder"></i> {0}', $customerDirectory->name), [
                        'escape' => false,
                        'class' => 'btn btn-link',
                        'title' => __('Ouvrir'),
                        'type' => 'button',
                        'data-toggle' => 'collapse',
                        'data-target' => __('#firm_{0}_dir_{1}_content', [$firm->id, $key]),
                        'aria-expanded' => 'false',
                        'aria-controls' => __('firm_{0}_dir_{1}_contnent', [$firm->id, $key])
                    ]) ?>
                    <?php if ($this->request->getSession()->read('Auth.User.user_type_id') != 3): ?>
                    <?= $this->Form->postLink(__('<i class="fas fa-trash-alt"></i>'), [
                        '_name' => 'deleteDirectory',
                        $customerDirectory->id
                    ], [
                        'escape' => false,
                        'class' => 'float-right',
                        'title' => __('Supprimer le dossier'),
                        'confirm' => __('Voulez-vous vraiment supprimer le dossier {0} ?', $customerDirectory->name)
                    ]) ?>
                    <?php endif; ?>
                </h5>
            </div>
            <div id=<?= __('firm_{0}_dir_{1}_content', [$firm->id, $key]) ?> class="collapse" aria-labelledby=<?= __('firm_{0}_dir_{1}_heading', [$firm->id, $key]) ?> data-parent=<?= __('#storage_firm_{0}', $firm->id) ?>>
                <div class="card-body">
                    <?php if ($customerDirectory->has('customer_files')): ?>
                    <ul class="list-group">
                        <?php foreach ($customerDirectory->customer_files as $customerFile): ?>
                        <li class="list-group-item">
                            <?= $this->Html->link(__('<i class="fas fa-file"></i> {0}', h($customerFile->name)), [
                                '_name' => 'downloadCustomerFile',
                                $customerFile->id
                            ], [
                                'escape' => false,
                                'title' => __('Télécharger')
                            ]) ?>
                            <?php if ($this->request->getSession()->read('Auth.User.user_type_id') != 3): ?>
                            <?= $this->Form->postLink(__('<i class="fas fa-trash-alt"></i>'), [
                                '_name' => 'deleteCustomerFile', 
                                $customerFile->id
                            ], [
                                'escape' => false,
                                'class' => 'float-right',
                                'title' => __('Supprimer le document'),
                                'confirm' => __('Voulez-vous vraiment supprimer le document {0}?', $customerFile->file->name)
                            ]) ?>
                            <?php endif; ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </section>
    <?php endif; ?>
    <?php if ((count($firm->storage->read()[1]) > 0) && !$this->Paginator->hasNext()): ?>
    <section>
        <ul class="list-group">
            <?php foreach ($firm->customer_files as $customerFile): ?>
            <?php  if (!$customerFile->has('customer_directory_id')): ?>
            <li class="list-group-item">
                <?= $this->Html->link(__('<i class="fas fa-file"></i> {0}', h($customerFile->name)), [
                    '_name' => 'downloadCustomerFile',
                    $customerFile->id
                ], [
                    'escape' => false,
                    'title' => __('Télécharger')
                ]) ?>
                <?php if ($this->request->getSession()->read('Auth.User.user_type_id') != 3): ?>
                <?= $this->Form->postLink(__('<i class="fas fa-trash-alt"></i>'), [
                    '_name' => 'deleteCustomerFile', 
                    $customerFile->id
                ], [
                    'escape' => false,
                    'class' => 'float-right',
                    'title' => __('Supprimer le document'),
                    'confirm' => __('Voulez-vous vraiment supprimer le document {0}?', $customerFile->file->name)
                ]) ?>
                <?php endif; ?>
            </li>
            <?php endif; ?>
            <?php endforeach; ?>
        </ul>
    </section>
    <?php endif; ?>
    <?php if ($firm->customer_directories_count > 0): ?>
    <section class="mt-2">
        <nav aria-label="customer-files list pagination">
            <ul class="pagination justify-content-center" id="storagePagination">
                <?= $this->Paginator->first('<< ' . __('Premier')) ?>
                <?= $this->Paginator->prev('< ' . __('Précédent')) ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next(__('Suivant') . ' >') ?>
                <?= $this->Paginator->last(__('Dernier') . ' >>') ?>
            </ul>
        </nav>
    </section>
    <?php endif; ?>
</div>
