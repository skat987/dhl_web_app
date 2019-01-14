<?php
/**
 * Display the storage content of a firm
 * 
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\CustomerFile[]|\Cake\Collection\CollectionInterface $customerFiles
 */
?>
<?php if (count($firm->storage->read()[0]) > 0):
    $storage = $firm->storage->read()[0];
    arsort($storage);
?>
<?php foreach ($storage as $key => $dir_name): ?>
<div class="card">
    <div class="card-header" id=<?= __('heading_{0}', $key) ?>>
        <div class="row">
            <div class="col-auto mr-auto">
                <?= $this->Form->button(__('<i class="far fa-folder"></i> {0}', $dir_name), [
                    'escape' => false,
                    'class' => 'btn btn-link',
                    'title' => __('Ouvrir'),
                    'type' => 'button',
                    'data-toggle' => 'collapse',
                    'data-target' => __('#collapse_{0}', $key),
                    'aria-expanded' => 'false',
                    'aria-controls' => __('collapse_{0}', $key)
                ]) ?>
            </div>
            <?php if ($this->request->getSession()->read('Auth.User.user_type_id') != 3): ?>
            <div class="col-auto">
                <?= $this->Form->postLink(__('<i class="far fa-trash-alt"></i>'), [
                    '_name' => 'deleteDirectory', 
                    'firm_id' => $firm->id, 
                    'dir_name' => $dir_name
                ], [
                    'escape' => false,
                    'title' => __('Supprimer le dossier'),
                    'confirm' => __('Voulez-vous vraiment supprimer le dossier {0}?', $dir_name)
                ]) ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <div id=<?= __('collapse_{0}', $key) ?> class="collapse" aria-labelledby=<?= __('heading_{0}', $key) ?> data-parent="#firmStorage">
        <div class="card-body">
            <ul class="list-group">
                <?php foreach ($customerFiles as $customerFile): ?>
                <?php if ($customerFile->file->Folder->inPath($firm->storage->cd($dir_name))): ?>
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
                        '_name' => 'deleteCustomerFile', $customerFile->id
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
<?php endif; ?>
<?php if (count($firm->storage->read()[1]) > 0): ?>
<ul class="list-group">
    <?php foreach($customerFiles as $customerFile): ?>
    <?php if ($customerFile->file->Folder->path == $firm->storage->path): ?>
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
            '_name' => 'deleteCustomerFile', $customerFile->id
        ], [
            'escape' => false,
            'class' => 'float-right',
            'title' => __('Télécharger'),
            'confirm' => __('Voulez-vous vraiment supprimer le document {0}?', $customerFile->file->name)
        ]) ?>
        <?php endif; ?>
    </li>
    <?php endif; ?>
    <?php endforeach; ?>
</ul>
<?php endif; ?>
<nav>
    <ul class="pagination justify-content-center">
        <?= $this->Paginator->first('<< ' . __('Premier')) ?>
        <?= $this->Paginator->prev('< ' . __('Précédent')) ?>
        <?= $this->Paginator->numbers() ?>
        <?= $this->Paginator->next(__('Suivant') . ' >') ?>
        <?= $this->Paginator->last(__('Dernier') . ' >>') ?>
    </ul>
    <p><?= $this->Paginator->counter(['format' => __('Page {{page}} sur {{pages}}, {{current}} enregistrement(s) affiché(s) sur {{count}}')]) ?></p>
    <?= $this->Paginator->generateUrl(['prevActive']) ?>
</nav>
