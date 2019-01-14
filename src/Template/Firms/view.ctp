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
                <div class="col-md-4">
                    <p class="lead text-center">
                        <span class="badge badge-outline-dark badge-pill"><?= $this->Number->format($firm->workers_count) ?></span>
                        <?= ($firm->workers_count > 1) ? 'utilisateurs associés' : 'utilisateur associé' ?>
                    </p>
                </div>
                <div class="col-md-4">
                    <p class="lead text-center">
                        <span class="badge badge-outline-dark badge-pill"><?= $this->Number->format(count($firm->storage->read()[0])) ?></span>
                        <?= (count($firm->storage->read()[0]) > 1) ? 'dossiers' : 'dossier' ?>
                    </p>
                </div>
                <div class="col-md-4">
                    <p class="lead text-center">
                        <span class="badge badge-outline-dark badge-pill"><?= $this->Number->format($firm->customer_files_count) ?></span>
                        <?= ($firm->customer_files_count > 1) ? 'documents' : 'document' ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($this->request->getSession()->read('Auth.User.user_type_id') != 3): ?>
<div class="row">
    <div class="col-md-6 mb-3 d-flex justify-content-center">
        <?= $this->Html->link(__('<i class="far fa-file"></i> Nouveau document <i class="fas fa-plus-circle"></i>'), '#', [
            'escape' => false,
            'role' => 'button',
            'class' => 'btn btn-outline-dark',
            'title' => 'Ajouter un document',
            'data-toggle' => 'modal',
            'data-target' => '#modal',
            'data-link' => $this->Url->build(['_name' => 'addCustomerFile', $firm->id]),
            'data-firm' => $firm->id
        ]) ?>
    </div>
    <div class="col-md-6 mb-3 d-flex justify-content-center">
        <?= $this->Html->link(__('<i class="far fa-folder"></i> Nouveau dossier <i class="fas fa-plus-circle"></i>'), '#', [
            'escape' => false,
            'role' => 'button',
            'class' => 'btn btn-outline-dark',
            'title' => 'Ajouter un dossier',
            'data-toggle' => 'modal',
            'data-target' => '#modal',
            'data-link' => $this->Url->build(['_name' => 'addDirectory', $firm->id]),
            'data-firm' => $firm->id
        ]) ?>
    </div>
</div>
<?php endif; ?>
<div class="row">
    <?php if ((count($firm->storage->read()[0]) > 0) || ($firm->customer_files_count > 0)): ?>
    <div class="accordion col" id="firmStorage" data-link=<?= $this->Url->build(['_name' => 'getStorage', $firm->id]) ?>>
        <!-- Storage content -->
    </div>
    <?php endif; ?>
</div>
