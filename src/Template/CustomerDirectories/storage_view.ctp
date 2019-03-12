<?php
/**
 * Display the storage content of a firm
 * 
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CustomerFile[]|\Cake\Collection\CollectionInterface $customerFiles
 */
?>
<div class="container-fluid clearfix">    
    <?php if ($firm->customer_directories_count > 0): ?>
    <section class="accordion" id=<?= __('storage_firm_{0}', $firm->id) ?>>
        <?php foreach ($customerDirectories as $customerDirectory): ?>
        <div class="card">
            <div class="card-header" id=<?= __('firm_{0}_dir_{1}_heading', [$firm->id, $customerDirectory->id]) ?>>
                <div class="row">
                    <h5 class="col-sm-4 mb-0">
                        <?= $this->Form->button(__('<i class="fas fa-folder"></i> {0}', substr($customerDirectory->name, strpos($customerDirectory->name, '_') + 1, strlen($customerDirectory->name))), [
                            'escape' => false,
                            'class' => 'btn btn-link custom-link',
                            'title' => __('Ouvrir'),
                            'type' => 'button',
                            'data-toggle' => 'collapse',
                            'data-target' => __('#firm_{0}_dir_{1}_content', [$firm->id, $customerDirectory->id]),
                            'aria-expanded' => 'false',
                            'aria-controls' => __('firm_{0}_dir_{1}_contnent', [$firm->id, $customerDirectory->id])
                        ]) ?>
                    </h5>
                    <div class="col-sm-4 py-0 d-flex align-items-center">
                        <p class="my-0"><?= h($customerDirectory->created->format('d/m/y')) ?></p>
                    </div>
                    <div class="col-sm-4 py-0 d-flex align-items-center justify-content-end">
                        <?php if ($this->request->getSession()->read('Auth.User.user_type_id') != 3): ?>
                            <?= $this->Html->link(__('<i class="fas fa-edit"></i>'), '#', [
                                'escape' => false,
                                'class' => 'float-right mr-2 custom-icon-link',
                                'title' => __('Renommer le dossier'),
                                'data-toggle' => 'modal',
                                'data-target' => '#modal',
                                'data-link' => $this->Url->build(['_name' => 'editDirectory', $customerDirectory->id], true)
                            ]) ?>
                            <?= $this->Form->postLink(__('<i class="fas fa-trash-alt"></i>'), [
                                '_name' => 'deleteDirectory',
                                $customerDirectory->id
                            ], [
                                'escape' => false,
                                'class' => 'float-right custom-icon-link',
                                'title' => __('Supprimer le dossier'),
                                'confirm' => __('Voulez-vous vraiment supprimer le dossier {0} ?', $customerDirectory->name)
                            ]) ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div id=<?= __('firm_{0}_dir_{1}_content', [$firm->id, $customerDirectory->id]) ?> class="collapse" aria-labelledby=<?= __('firm_{0}_dir_{1}_heading', [$firm->id, $customerDirectory->id]) ?> data-parent=<?= __('#storage_firm_{0}', $firm->id) ?>>
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
                                'class' => 'custom-link',
                                'title' => __('Télécharger')
                            ]) ?>
                            <?php if ($this->request->getSession()->read('Auth.User.user_type_id') != 3): ?>
                            <?= $this->Form->postLink(__('<i class="fas fa-trash-alt"></i>'), [
                                '_name' => 'deleteCustomerFile', 
                                $customerFile->id
                            ], [
                                'escape' => false,
                                'class' => 'float-right custom-icon-link',
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
    <?php if (isset($firm->customer_files)): ?>
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
                    'class' => 'custom-link',
                    'title' => __('Télécharger')
                ]) ?>
                <?php if ($this->request->getSession()->read('Auth.User.user_type_id') != 3): ?>
                <?= $this->Form->postLink(__('<i class="fas fa-trash-alt"></i>'), [
                    '_name' => 'deleteCustomerFile', 
                    $customerFile->id
                ], [
                    'escape' => false,
                    'class' => 'float-right custom-icon-link',
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
    <?php endif; ?>
    <?php if ($firm->customer_directories_count > 0): ?>
    <section class="mt-2">
        <nav aria-label="customer-files list pagination">
            <ul class="pagination justify-content-center mb-0" id="storagePagination">
                <?= $this->Paginator->first('<< ' . __('Premier')) ?>
                <?= $this->Paginator->prev('< ' . __('Précédent')) ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next(__('Suivant') . ' >') ?>
                <?= $this->Paginator->last(__('Dernier') . ' >>') ?>
            </ul>
            <div class="col d-flex justify-content-center">
                <small class="pagination-help text-muted">
                    <?= $this->Paginator->counter(['format' => 'Page {{page}}/{{pages}} des dossiers']) ?>
                </small>
            </div>
        </nav>
    </section>
    <?php endif; ?>
</div>
