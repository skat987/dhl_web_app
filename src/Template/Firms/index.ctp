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
<div class="row">
    <div class="col">
        <?= $this->Html->link(__('Nouvelle société <i class="fas fa-plus-circle"></i>'), '#', [
            'escape' => false,
            'data-toggle' => 'modal',
            'data-target' => '#modal',
            'data-link' => $this->Url->build(['_name' => 'addFirm']),
            'role' => 'button',
            'class' => 'btn btn-outline-dark mt-3 mb-3',
            'title' => __('Ajouter une société')
        ]) ?>
    </div>
</div>
<div class="row">	
    <div class="col">
        <div class="accordion" id="allFirms">
            <?php foreach ($firms as $firmKey => $firm): ?>
            <div class="card">
                <div class="card-header" id=<?= __('firm_{0}_heading', $firm->id) ?>>
                    <div class="row">
                        <div class="col-4">
                            <h5 class="mb-0">
                                <?= $this->Form->button(h($firm->name), [
                                    'class' => 'btn btn-link',
                                    'id' => __('firm_btn_{0}', $firmKey),
                                    'title' => __('Ouvrir'),
                                    'type' => 'button',
                                    'data-toggle' => 'collapse',
                                    'data-target' => __('#firm_{0}_storage', $firm->id),
                                    'aria-expanded' => 'false',
                                    'aria-controls' => __('firm_{0}_storage', $firm->id),
                                    'data-link' => $this->Url->build(['_name' => 'getStorage', $firm->id])
                                ]) ?>
                            </h5>
                        </div>
                        <div class="col-4">
                            <p class="mb-0">
                                <span class="badge badge-outline-dark badge-pill"><?= $this->Number->format($firm->workers_count) ?></span>
                                <?= ($firm->workers_count > 1) ? __('utilisateurs associés') : __('utilisateur associé') ?>
                            </p>
                            <p class="mb-0">
                                <span class="badge badge-outline-dark badge-pill"><?= $this->Number->format(count($firm->storage->read()[0])) ?></span>
                                <?= (count($firm->storage->read()[0]) > 1) ? __('dossiers') : __('dossier') ?>
                            </p>
                            <p class="mb-0">
                                <span class="badge badge-outline-dark badge-pill"><?= $this->Number->format($firm->customer_files_count) ?></span>
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
                                <?= $this->Html->link('<i class="far fa-eye"></i>', [
                                    '_name' => 'viewFirm', 
                                    $firm->id
                                ], [
                                    'escape' => false,
                                    'class' => 'mr-2',
                                    'title' => __('Accéder à l\'espace client')
                                ]) ?>
                                <?= $this->Html->link(__('<i class="far fa-edit"></i>'), '#', [
                                    'escape' => false,
                                    'class' => 'mr-2',
                                    'title' => __('Renommer la société'),
                                    'data-toggle' => 'modal',
                                    'data-target' => '#modal',
                                    'data-link' => $this->Url->build(['_name' => 'editFirm', $firm->id])
                                ]) ?>
                                <?= $this->Form->postLink('<i class="far fa-trash-alt"></i>', [
                                    '_name' => 'deleteFirm', 
                                    $firm->id
                                ], [
                                    'escape' => false,
                                    'title' => __('Supprimer la société'),
                                    'confirm' => __('Voulez-vous vraiment supprimer la société {0} ?', $firm->name)
                                ]) ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div id=<?= __('firm_{0}_storage', $firm->id) ?> class="collapse" aria-labelledby=<?= __('firm_{0}_heading', $firm->id) ?> data-parent="#allFirms">
                    <div class="card-body">
                        <!-- Storage content -->
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<nav>
    <ul class="pagination justify-content-center">
        <?= $this->Paginator->first('<< ' . __('Premier')) ?>
        <?= $this->Paginator->prev('< ' . __('Précédent')) ?>
        <?= $this->Paginator->numbers() ?>
        <?= $this->Paginator->next(__('Suivant') . ' >') ?>
        <?= $this->Paginator->last(__('Dernier') . ' >>') ?>
    </ul>
    <p><?= $this->Paginator->counter(['format' => __('Page {{page}} sur {{pages}}, {{current}} enregistrement(s) affiché(s) sur {{count}}')]) ?></p>
</nav>
