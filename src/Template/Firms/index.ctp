<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Firm[]|\Cake\Collection\CollectionInterface $firms
 */

$this->assign('title', 'Liste des sociétés');
// Call the Modal element
echo $this->element('modal');
?>
<div class="row">
    <div class="col">
        <?= $this->Html->link(__('Ajouter une société <i class="fas fa-plus-circle"></i>'), '#', [
            'escape' => false,
            'data-toggle' => 'modal',
            'data-target' => '#modal',
            'data-link' => $this->Url->build(['_name' => 'adminFirmAdd']),
            'role' => 'button',
            'class' => 'btn btn-outline-dark'
        ]) ?>
        <?= $this->Html->link(__('Ajouter un document <i class="fas fa-plus-circle"></i>'), '#', [
            'escape' => false,
            'data-toggle' => 'modal',
            'data-target' => '#modal',
            'data-link' => $this->Url->build(['_name' => 'addCustomerFile']),
            'role' => 'button',
            'class' => 'btn btn-outline-dark'
        ]) ?>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="accordion" id="firmsList">
            <?php foreach ($firms as $firm): ?>
            <div class="card">
                <div class="card-header" id=<?= __('heading_') . $firm->id ?>>
                    <div class="row">
                        <div class="col-4">
                            <h5 class="mb-0">
                                <?= $this->Form->button(h($firm->name), [
                                    'class' => 'btn btn-link',
                                    'type' => 'button',
                                    'data-toggle' => 'collapse',
                                    'data-target' => '#collapse_' . $firm->id,
                                    'aria-expanded' => 'false',
                                    'aria-controls' => 'collapse_' . $firm->id
                                ]) ?>
                            </h5>
                        </div>
                        <div class="col-4">
                            <p class="mb-0">
                                <span class="badge badge-outline-dark badge-pill"><?= $this->Number->format($firm->workers_count) ?></span>
                                <?= __(' utilisateurs associés') ?>
                            </p>
                            <p class="mb-0">
                                <span class="badge badge-outline-dark badge-pill"><?= $this->Number->format($firm->customer_files_count) ?></span>
                                <?= __(' documents') ?>
                            </p>
                        </div>
                        <div class="col-4">
                            <div class="row">
                                <div class="col">
                                    <h6 class="text-center font-weight-bold"><?= __('Actions') ?></h6>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <?= $this->Html->link('<i class="far fa-eye"></i>', [
                                    '_name' => 'firmView', 
                                    $firm->id
                                ], [
                                    'escape' => false
                                ]) ?>
                                <?= $this->Html->link('<i class="far fa-edit"></i>', '#', [
                                    'escape' => false,
                                    'data-toggle' => 'modal',
                                    'data-target' => '#modal',
                                    'data-link' => $this->Url->build(['_name' => 'adminFirmEdit', $firm->id]),
                                    'data-id' => $firm->id
                                ]) ?>
                                <?= $this->Form->postLink('<i class="far fa-trash-alt"></i>', [
                                    '_name' => 'adminFirmDelete', $firm->id
                                ], [
                                    'escape' => false,
                                    'confirm' => __('Voulez-vous vraiment supprimer la société {0}?', $firm->name)
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (($firm->customer_files_count > 0)): ?>
                <div id=<?= __('collapse_') . $firm->id ?> class="collapse" aria-labelledby=<?= __('heading_') . $firm->id ?> data-parent="#firmsList">
                    <div class="card-body">
                        <div class="accordion" id=<?= __('storage_firm_') . $firm->id ?>>
                            <?php if (count($firm->dir->read()[0]) > 0): ?>
                            <?php foreach($firm->dir->read()[0] as $key => $subDir): ?>
                            <div class="card">
                                <div class="card-header" id=<?= __('heading_storage_') . $firm->id . __('_dir_') . $key ?>>
                                <h5 class="mb-0">
                                    <?= $this->Form->button(__('<i class="far fa-folder"></i> ') . h($subDir), [
                                        'escape' => false,
                                        'class' => 'btn btn-link',
                                        'type' => 'button',
                                        'data-toggle' => 'collapse',
                                        'data-target' => '#collapse_storage_' . $firm->id . __('_dir_') . $key,
                                        'aria-expanded' => 'false',
                                        'aria-controls' => 'collapse_storage_' . $firm->id . __('_dir_') . $key
                                    ]) ?>
                                </h5>
                                </div>
                                <div id=<?= __('collapse_storage_') . $firm->id . __('_dir_') . $key ?> class="collapse" aria-labelledby=<?= __('heading_storage_') . $firm->id . __('_dir_') . $key ?> data-parent=<?= __('#storage_firm_') . $firm->id ?>>
                                    <div class="card-body">
                                        <ul class="list-group">
                                            <?php foreach($firm->customer_files as $customerFile): ?>
                                            <?php if($customerFile->file->Folder->inPath($firm->dir->cd($subDir))): ?>
                                            <li class="list-group-item">
                                                <?= $this->Html->link(__('<i class="far fa-file"></i> ') . h($customerFile->file->name()), 
                                                    'uploads' . DS . $firm->id . DS . $customerFile->dir_name . DS . $customerFile->file->name, 
                                                    ['escape' => false]
                                                ) ?>
                                                <?= $this->Form->postLink(__('<i class="far fa-trash-alt"></i>'), [
                                                    '_name' => 'deleteCustomerFile', $customerFile->id
                                                ], [
                                                    'escape' => false,
                                                    'class' => 'float-right',
                                                    'confirm' => __('Voulez-vous vraiment supprimer le document {0}?', $customerFile->file_name)
                                                ]) ?>
                                            </li>
                                            <?php endif; ?>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                            <?php endif; ?>
                            <?php if (count($firm->dir->read()[1]) > 0): ?>
                            <ul class="list-group">
                                <?php foreach($firm->customer_files as $customerFile): ?>
                                <?php if($customerFile->file->Folder->path == $firm->dir->path): ?>
                                <li class="list-group-item">
                                    <?= $this->Html->link(_('<i class="far fa-file"></i> ') . h($customerFile->file->name()),
                                        'uploads' . DS . $firm->id . DS . $customerFile->file->name,
                                        ['escape' => false]
                                    ) ?>
                                    <?= $this->Form->postLink(__('<i class="far fa-trash-alt"></i>'), [
                                        '_name' => 'deleteCustomerFile', $customerFile->id
                                    ], [
                                        'escape' => false,
                                        'class' => 'float-right',
                                        'confirm' => __('Voulez-vous vraiment supprimer le document {0}?', $customerFile->file_name)
                                    ]) ?>
                                </li>
                                <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
