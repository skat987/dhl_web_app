<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Firm[]|\Cake\Collection\CollectionInterface $firms
 */

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
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="accordion" id="firmsList">
            <?php foreach ($firms as $firm):?>
            <div class="card">
                <div class="card-header" id=<?= __("heading_") . $firm->id ?>>
                    <div class="row">
                        <div class="col-4">
                            <h5 class="mb-0">
                                <?= $this->Form->button(__(h($firm->name)), [
                                    'class' => 'btn btn-link',
                                    'type' => 'button',
                                    'data-toggle' => 'collapse',
                                    'data-target' => '#collapse_' . $firm->id,
                                    'aria-expanded' => 'false',
                                    'aria-controls' => 'collapse_' .$firm->id
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
                                <?= $this->Html->link('<i class="far fa-eye"></i>', ['_name' => 'firmView', $firm->id], ['escape' => false]) ?>
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
                <?php if ($firm->customer_files_count > 0): ?>
                <div id=<?= __('collapse_') . $firm->id ?> class="collapse" aria-labelledby=<?= __("heading_") . $firm->id ?> data-parent="#firmsList">
                    <div class="card-body">
                        <ul class="list-group">
                            <?php foreach ($firm->customer_files as $customerFile): ?>
                            <li class="list-group-item"><?= h($customerFile->file_name) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
