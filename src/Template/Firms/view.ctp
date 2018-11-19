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
    <?php if (isset($dir) && (count($dir) > 0)): ?>
    <div class="accordion col" id="accordion_firms_view">
        <?php if (isset($dir['subDirs'])): ?>
        <?php foreach ($dir['subDirs'] as $key => $subDir): ?>
        <div class="card">
            <div class="card-header" id=<?= __('accordion_firms_view_') . $key ?>>
                <h5 class="mb-0">
                    <?= $this->Form->button(__('<i class="far fa-folder"></i> ') . h($subDir['name']), [
                        'escape' => false,
                        'class' => 'btn btn-link',
                        'type' => 'button',
                        'data-toggle' => 'collapse',
                        'data-target' => '#collapse_firms_view_' . $key,
                        'aria-expanded' => 'false',
                        'aria-controls' => 'collapse_firms_view_' . $key
                    ]) ?>
                </h5>
            </div>
            <div id=<?= __('collapse_firms_view_') . $key ?> class="collapse" aria-labelledby=<?= __('accordion_firms_view_') . $key ?> data-parent="#accordion_firms_view">
                <div class="card-body">
                    <ul class="list-group">
                        <?php if (isset($subDir['files'])): ?>
                        <?php foreach ($subDir['files'] as $file): ?>
                        <li class="list-group-item"><i class="far fa-file"></i><?= __(' ') . h($file->name()) ?></li>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <li class="list-group-item"><?= __('Dossier vide') ?></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
        <?php if (isset($dir['files'])): ?>
        <ul class="list-group">
            <?php foreach($dir['files'] as $singleFile): ?>
            <li class="list-group-item"><i class="far fa-file"></i><?= __(' ') . h($singleFile->name()) ?></li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
