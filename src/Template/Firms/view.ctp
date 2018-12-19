<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Firm $firm
 */

$this->assign('title', 'Espace client');
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
                <div class="col-md-6 mb-3">
                    <p class="lead text-center">
                        <span class="badge badge-outline-dark badge-pill"><?= $this->Number->format($firm->workers_count) ?></span>
                        <?= __(' utilisateurs associés') ?>
                    </p>
                </div>
                <div class="col-md-6 mb-3">
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
    <?php if ($firm->customer_files_count > 0): ?>
    <div class="accordion col" id="firmStorage">
        <?php if (count($firm->dir->read()[0]) > 0): ?>
        <?php foreach ($firm->dir->read()[0] as $key => $subDir): ?>
        <div class="card">
            <div class="card-header" id=<?= __('heading_') . $key ?>>
                <h5 class="mb-0">
                    <?= $this->Form->button(__('<i class="far fa-folder"></i> ') . h($subDir), [
                        'escape' => false,
                        'class' => 'btn btn-link',
                        'type' => 'button',
                        'data-toggle' => 'collapse',
                        'data-target' => '#collapse_' . $key,
                        'aria-expanded' => 'false',
                        'aria-controls' => 'collapse_' . $key
                    ]) ?>
                </h5>
            </div>
            <div id=<?= __('collapse_') . $key ?> class="collapse" aria-labelledby=<?= __('heading_') . $key ?> data-parent="#firmStorage">
                <div class="card-body">
                    <ul class="list-group">
                        <?php foreach ($firm->customer_files as $customerFile): ?>
                        <?php if ($customerFile->file->Folder->inPath($firm->dir->cd($subDir))): ?>
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
            <?php if ($customerFile->file->Folder->path == $firm->dir->path): ?>
            <li class="list-group-item">
                <?= $this->Html->link(__('<i class="far fa-file"></i> ') . h($customerFile->file->name()),
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
    <?php endif; ?>
</div>
