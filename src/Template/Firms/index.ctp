<?php
/**
 * List of firms.
 * 
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Firm[]|\Cake\Collection\CollectionInterface $firms
 */
$this->assign('title', 'Liste des sociétés');
// Call the Modal element
echo $this->element('modal');
?>
<section class="row">
    <div class="col">
        <?= $this->Html->link(__('<i class="fas fa-building"></i> Nouvelle société <i class="fas fa-plus-circle"></i>'), '#', [
            'escape' => false,
            'data-toggle' => 'modal',
            'data-target' => '#modal',
            'data-link' => $this->Url->build(['_name' => 'addFirm'], true),
            'role' => 'button',
            'class' => 'dhl-custom-btn btn btn-block mt-3 mb-3',
            'title' => __('Ajouter une société')
        ]) ?>
    </div>
</section>
<section class="row">	
    <div class="col">
        <div class="accordion" id="allFirms">
            <?php foreach ($firms as $firmKey => $firm): ?>
            <div class="card">
                <div class="card-header firm-card-header" id=<?= __('firm_{0}_heading', $firm->id) ?>>
                    <div class="row">
                        <div class="col-4">
                            <h5 class="mb-0">
                                <?= $this->Form->button(__('<i class="fas fa-building"></i> {0}', $firm->name), [
                                    'class' => 'btn btn-link custom-link',
                                    'id' => __('firm_btn_{0}', $firmKey),
                                    'title' => __('Ouvrir'),
                                    'type' => 'button',
                                    'data-toggle' => 'false',
                                    'data-target' => __('#firm_{0}_storage', $firm->id),
                                    'aria-expanded' => 'false',
                                    'aria-controls' => __('firm_{0}_storage', $firm->id)
                                ]) ?>
                            </h5>
                        </div>
                        <div class="col-4">
                            <p class="mb-0">
                                <span class="badge badge-outline-dark badge-pill"><?= $this->Number->format($firm->workers_count) ?></span>
                                <?= ($firm->workers_count > 1) ? __('utilisateurs associés') : __('utilisateur associé') ?>
                            </p>
                            <p class="mb-0">
                                <span class="badge badge-outline-dark badge-pill"><?= $this->Number->format($firm->customer_directories_count) ?></span>
                                <?= ($firm->customer_directories_count > 1) ? __('dossiers') : __('dossier') ?>
                            </p>
                            <p class="mb-0">
                                <span class="badge badge-outline-dark badge-pill" id=<?= __('filesCount-firm-{0}', $firm->id) ?>><?= $this->Number->format($firm->customer_files_count) ?></span>
                                <?= ($firm->customer_files_count > 1) ? __('documents') : __('document') ?>
                            </p>
                        </div>
                        <div class="col-4">
                            <div class="row">
                                <div class="col">
                                    <h6 class="text-center font-weight-bold"><?= __('Actions') ?></h6>
                                </div>
                            </div>
                            <div class="row justify-content-center">
                                <?= $this->Html->link('<i class="fas fa-eye"></i>', [
                                    '_name' => 'viewFirm', 
                                    $firm->id
                                ], [
                                    'escape' => false,
                                    'class' => 'mr-2 custom-icon-link',
                                    'title' => __('Accéder à l\'espace client')
                                ]) ?>
                                <?= $this->Html->link(__('<i class="fas fa-edit"></i>'), '#', [
                                    'escape' => false,
                                    'class' => 'mr-2 custom-icon-link',
                                    'title' => __('Renommer la société'),
                                    'data-toggle' => 'modal',
                                    'data-target' => '#modal',
                                    'data-link' => $this->Url->build(['_name' => 'editFirm', $firm->id], true)
                                ]) ?>
                                <?= $this->Form->postLink('<i class="fas fa-trash-alt"></i>', [
                                    '_name' => 'deleteFirm', 
                                    $firm->id
                                ], [
                                    'escape' => false,
                                    'class' => 'custom-icon-link',
                                    'title' => __('Supprimer la société'),
                                    'confirm' => __('Voulez-vous vraiment supprimer la société {0} ?', $firm->name)
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div id=<?= __('firm_{0}_storage', $firm->id) ?> class="collapse" aria-labelledby=<?= __('firm_{0}_heading', $firm->id) ?> data-parent="#allFirms">
                    <div class="card-body container-fluid clearfix">
                        <?= $this->element('panel', ['firm' => $firm, 'firmKey' => $firmKey]) ?>
                        <div id=<?= __('storageContent_{0}', $firmKey) ?> data-link=<?= $this->Url->build(['_name' => 'getStorage', $firm->id, 'all'], true) ?>>
                            <!-- Storage content -->
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<section class="mt-2 mb-3">
    <nav aria-label="firms list pagination">
        <ul class="pagination justify-content-center mb-0">
            <?= $this->Paginator->first('<< ' . __('Premier')) ?>
            <?= $this->Paginator->prev('< ' . __('Précédent')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('Suivant') . ' >') ?>
            <?= $this->Paginator->last(__('Dernier') . ' >>') ?>
        </ul>
        <div class="col d-flex justify-content-center">
            <small class="pagination-help text-muted">
                <?= $this->Paginator->counter(['format' => 'Page {{page}}/{{pages}} des sociétés']) ?>
            </small>
        </div>
    </nav>
</section>
