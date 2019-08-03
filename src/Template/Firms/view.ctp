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
<section class="row">
    <div class="jumbotron jumbotron-fluid col py-4">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h1 class="text-center"><?= h($firm->name) ?></h1>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <p class="lead text-center">
                        <span class="badge badge-outline-dark badge-pill"><?= $this->Number->format($firm->workers_count) ?></span>
                        <?= ($firm->workers_count > 1) ? 'utilisateurs associés' : 'utilisateur associé' ?>
                    </p>
                </div>
                <div class="col-md-4">
                    <p class="lead text-center">
                        <span class="badge badge-outline-dark badge-pill"><?= $this->Number->format($firm->customer_directories_count) ?></span>
                        <?= ($firm->customer_directories_count > 1) ? 'dossiers' : 'dossier' ?>
                    </p>
                </div>
                <div class="col-md-4">
                    <p class="lead text-center">
                        <span class="badge badge-outline-dark badge-pill" id=<?= __('filesCount-firm-{0}', $firm->id) ?>><?= $this->Number->format($firm->customer_files_count) ?></span>
                        <?= ($firm->customer_files_count > 1) ? 'documents' : 'document' ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="container-fluid clearfix">
    <?= $this->element('panel', ['firm' => $firm, 'firmKey' => $firm->id]) ?>
    <div id="storageContent" data-link=<?= $this->Url->build(['_name' => 'getStorage', $firm->id, 'all'], true) ?> data-firm=<?= h($firm->id) ?>>
        <!-- Storage content -->
    </div>
</section>
