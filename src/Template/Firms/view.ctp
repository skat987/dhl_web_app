<?php
/**
 * Space of a firm.
 * 
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Firm $firm
 */

$this->assign('title', 'Espace client');
// Call the Modal element
echo $this->element('modal');
?>
<div class="row">
    <div class="jumbotron jumbotron-fluid col">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h1 class="display-4 text-center"><?= h($firm->name) ?></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-2">
                    <p class="lead text-center">
                        <span class="badge badge-outline-dark badge-pill"><?= $this->Number->format($firm->workers_count) ?></span>
                        <?= __(' utilisateurs associés') ?>
                    </p>
                </div>
                <div class="col-md-4 mb-2">
                    <p class="lead text-center">
                        <span class="badge badge-outline-dark badge-pill"><?= $this->Number->format(count($firm->storage->read()[0])) ?></span>
                        <?= __(' dossiers') ?>
                    </p>
                </div>
                <div class="col-md-4 mb-2">
                    <p class="lead text-center">
                        <span class="badge badge-outline-dark badge-pill"><?= $this->Number->format($firm->customer_files_count) ?></span>
                        <?= __(' documents') ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col d-flex justify-content-center">
        <?= $this->Html->link(__('<i class="far fa-file"></i> Ajouter un document <i class="fas fa-plus-circle"></i>'), '#', [
            'escape' => false,
            'role' => 'button',
            'class' => 'btn btn-outline-dark',
            'data-toggle' => 'modal',
            'data-target' => '#modal',
            'data-link' => $this->Url->build(['_name' => 'addCustomerFile', $firm->id]),
            'data-firm' => $firm->id
        ]) ?>
        <?= $this->Html->link(__('<i class="far fa-folder"></i> Nouveau dossier <i class="fas fa-plus-circle"></i>'), '#', [
            'escape' => false,
            'role' => 'button',
            'class' => 'btn btn-outline-dark',
            'data-toggle' => 'modal',
            'data-target' => '#modal',
            'data-link' => $this->Url->build(['_name' => 'addDirectory', $firm->id]),
            'data-firm' => $firm->id
        ]) ?>
    </div>
</div>
<div class="row">
    <?php if ((count($firm->storage->read()[0]) > 0) || ($firm->customer_files_count > 0)): ?>
    <div class="accordion col" id="firmStorage">
        <?php if (count($firm->storage->read()[0]) > 0): ?>
        <?php foreach ($firm->storage->read()[0] as $key => $dir_name): ?>
        <div class="card">
            <div class="card-header" id=<?= __('heading_') . $key ?>>
                <div class="row">
                    <div class="col-auto mr-auto">
                        <?= $this->Form->button(__('<i class="far fa-folder"></i> ') . h($dir_name), [
                            'escape' => false,
                            'class' => 'btn btn-link',
                            'type' => 'button',
                            'data-toggle' => 'collapse',
                            'data-target' => '#collapse_' . $key,
                            'aria-expanded' => 'false',
                            'aria-controls' => 'collapse_' . $key
                        ]) ?>
                    </div>
                    <div class="col-auto">
                        <?= $this->Form->postLink(__('<i class="far fa-trash-alt"></i>'), [
                            '_name' => 'deleteDirectory', 'firm_id' => $firm->id, 'dir_name' => $dir_name
                        ], [
                            'escape' => false,
                            'confirm' => __('Voulez-vous vraiment supprimer le dossier {0}?', $dir_name)
                        ]) ?>
                    </div>
                </div>
            </div>
            <div id=<?= __('collapse_') . $key ?> class="collapse" aria-labelledby=<?= __('heading_') . $key ?> data-parent="#firmStorage">
                <div class="card-body">
                    <ul class="list-group">
                        <?php foreach ($firm->customer_files as $customerFile): ?>
                        <?php if ($customerFile->file->Folder->inPath($firm->storage->cd($dir_name))): ?>
                        <li class="list-group-item">
                            <?= $this->Html->link(__('<i class="far fa-file"></i> ') . h($customerFile->file_name),
                                'uploads' . DS . $firm->id . DS . $customerFile->dir_name . DS . $customerFile->file->name,
                                ['escape' => false]
                            ) ?>
                            <?= $this->Form->postLink(__('<i class="far fa-trash-alt"></i>'), [
                                '_name' => 'deleteCustomerFile', $customerFile->id
                            ], [
                                'escape' => false,
                                'class' => 'float-right',
                                'confirm' => __('Voulez-vous vraiment supprimer le document {0}?', $customerFile->file->name)
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
        <?php if (count($firm->storage->read()[1]) > 0): ?>
        <ul class="list-group">
            <?php foreach($firm->customer_files as $customerFile): ?>
            <?php if ($customerFile->file->Folder->path == $firm->storage->path): ?>
            <li class="list-group-item">
                <?= $this->Html->link(__('<i class="far fa-file"></i> ') . h($customerFile->file_name),
                    'uploads' . DS . $firm->id . DS . $customerFile->file->name,
                    ['escape' => false]
                ) ?>
                <?= $this->Form->postLink(__('<i class="far fa-trash-alt"></i>'), [
                    '_name' => 'deleteCustomerFile', $customerFile->id
                ], [
                    'escape' => false,
                    'class' => 'float-right',
                    'confirm' => __('Voulez-vous vraiment supprimer le document {0}?', $customerFile->file->name)
                ]) ?>
            </li>
            <?php endif; ?>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
