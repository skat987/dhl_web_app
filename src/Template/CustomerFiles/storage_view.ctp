<?php
/**
 * Display the storage content of a firm
 * 
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CustomerFile[]|\Cake\Collection\CollectionInterface $customerFiles
 */
?>
<?php if ($this->request->getSession()->read('Auth.User.user_type_id') != 3): ?>
<div class="row">
    <div class="col-md-6 mb-3 d-flex justify-content-center">
        <?= $this->Html->link(__('<i class="far fa-file"></i> Nouveau document <i class="fas fa-plus-circle"></i>'), '#', [
            'escape' => false,
            'role' => 'button',
            'class' => 'btn btn-outline-dark',
            'title' => __('Ajouter un document à la société {0}', $firm->name),
            'data-toggle' => 'modal',
            'data-target' => '#modal',
            'data-link' => $this->Url->build(['_name' => 'addCustomerFile', $firm->id], true)
        ]) ?>
    </div>
    <div class="col-md-6 mb-3 d-flex justify-content-center">
        <?= $this->Html->link(__('<i class="far fa-folder"></i> Nouveau dossier <i class="fas fa-plus-circle"></i>'), '#', [
            'escape' => false,
            'role' => 'button',
            'class' => 'btn btn-outline-dark',
            'title' => __('Ajouter un dossier à la société {0}', $firm->name),
            'data-toggle' => 'modal',
            'data-target' => '#modal',
            'data-link' => $this->Url->build(['_name' => 'addDirectory', $firm->id], true)
        ]) ?>
    </div>
</div>
<?php endif; ?>
<?php if (($firm->customer_files_count > 0) || (count($firm->storage->read()[0]) > 0)): ?>
<?php if (count($firm->storage->read()[0]) > 0): 
    $directories = $firm->storage->read()[0]; 
    arsort($directories); 
?>
<div class="accordion" id=<?= __('storage_firm_{0}', $firm->id) ?>>    
    <?php foreach ($directories as $key => $directory): ?>
    <div class="card">
        <div class="card-header" id=<?= __('firm_{0}_dir_{1}_heading', [$firm->id, $key]) ?>>
            <h5 class="mb-0">
                <?= $this->Form->button(__('<i class="far fa-folder"></i> {0}', $directory), [
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
                <?= $this->Form->postLink(__('<i class="far fa-trash-alt"></i>'), [
                    '_name' => 'deleteDirectory',
                    'firm_id' => $firm->id,
                    'dir_name' => $directory
                ], [
                    'escape' => false,
                    'class' => 'float-right',
                    'title' => __('Supprimer le dossier'),
                    'confirm' => __('Voulez-vous vraiment supprimer le dossier {0} ?', $directory)
                ]) ?>
                <?php endif; ?>
            </h5>
        </div>
        <div id=<?= __('firm_{0}_dir_{1}_content', [$firm->id, $key]) ?> class="collapse" aria-labelledby=<?= __('firm_{0}_dir_{1}_heading', [$firm->id, $key]) ?> data-parent=<?= __('#storage_firm_{0}', $firm->id) ?>>
            <div class="card-body">
                <ul class="list-group">
                    <?php foreach ($customerFiles as $customerFile): ?>
                    <?php if ($customerFile->has('dir_name') && ($customerFile->dir_name == $directory)): ?>
                    <li class="list-group-item">
                        <?= $this->Html->link(__('<i class="far fa-file"></i> {0}', h($customerFile->file_name)), [
                            '_name' => 'downloadCustomerFile',
                            $customerFile->id
                        ], [
                            'escape' => false,
                            'title' => __('Télécharger')
                        ]) ?>
                        <?php if ($this->request->getSession()->read('Auth.User.user_type_id') != 3): ?>
                        <?= $this->Form->postLink(__('<i class="far fa-trash-alt"></i>'), [
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
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<?php if (count($firm->storage->read()[1]) > 0): ?>
<ul class="list-group">
    <?php foreach ($customerFiles as $customerFile): ?>
    <?php if (!$customerFile->has('dir_name')): ?>
    <li class="list-group-item">
        <?= $this->Html->link(__('<i class="far fa-file"></i> {0}', h($customerFile->file_name)), [
            '_name' => 'downloadCustomerFile',
            $customerFile->id
        ], [
            'escape' => false,
            'title' => __('Télécharger')
        ]) ?>
        <?php if ($this->request->getSession()->read('Auth.User.user_type_id') != 3): ?>
        <?= $this->Form->postLink(__('<i class="far fa-trash-alt"></i>'), [
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
<?php endif; ?>
<nav>
    <ul class="pagination justify-content-center" id="customerFilesPagination">
        <?= $this->Paginator->first('<< ' . __('Premier')) ?>
        <?= $this->Paginator->prev('< ' . __('Précédent')) ?>
        <?= $this->Paginator->numbers() ?>
        <?= $this->Paginator->next(__('Suivant') . ' >') ?>
        <?= $this->Paginator->last(__('Dernier') . ' >>') ?>
    </ul>
    <p><?= $this->Paginator->counter(['format' => __('Page {{page}} sur {{pages}}, {{current}} enregistrement(s) affiché(s) sur {{count}}')]) ?></p>
</nav>
<?php endif; ?>
