<?php
/**
 * Display an item corresponding to a file 
 */
?>
<?= $this->Html->link(__('<i class="fas fa-file"></i> {0}', $customerFileName), [
    '_name' => 'downloadCustomerFile',
    $firmId,
    $customerFileId
], [
    'escape' => false,
    'class' => 'custom-link',
    'title' => __('Télécharger')
]) ?>
<?php if ($this->request->getSession()->read('Auth.User.user_type_id') != 3): ?>
<?= $this->Form->postButton(__('<i class="fas fa-trash-alt"></i>'), [
    '_name' => 'deleteCustomerFile', 
    $customerFileId
], [
    'escape' => false,
    'id' => __('deleteBtn-file-{0}', $customerFileId),
    'class' => 'float-right custom-icon-link delete-customer-file-link btn btn-link',
    'title' => __('Supprimer le document'),
    'data-filename' => __('{0}.{1}', [$customerFileName, $customerFileExt]),
    'onclick' => __('deleteFileItem({0})', $customerFileId)
]) ?>
<?php endif; ?>
<script>
    function deleteFileItem(id) {
        var btn = $('#deleteBtn-file-' + id);
        var form = $(btn).parent();
        if (confirm('Voulez-vous vraiment supprimer le document ' + $(btn).data('filename') + ' ?')) {
            $(form).submit(function(e) {
                e.preventDefault();
                e.stopPropagation();
                var f = $(this);
                $.post({
                    url: $(form).prop('action'),
                    dataType: 'json',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', $(form).find('[name="_csrfToken"]').val());
                    },
                    success: function(resp) {
                            if (resp.result == 'success') {
                                $(form).parent().remove();
                                $('#filesCount-firm-' + resp.firmId).text(resp.filesCount);
                                $.alert(resp.text, {
                                    autoClose: true,
                                    closeTime: 3000,
                                    type: 'success',
                                    position: ['top-right']
                                });
                            } else {
                                $.alert(resp.text, {
                                    autoClose: true,
                                    closeTime: 3000,
                                    type: 'warning',
                                    position:['bottom-right']
                                });
                            }
                        },
                        error: function(resp) {
                            console.log('Erreur', resp);
                            $.alert('Le document ' + $(form).children().last().data('filename') + ' n\'a pas pu être supprimé.', {
                                autoClose: true,
                                closeTime: 3000,
                                type: 'warning',
                                position: ['bottom-right']
                            });
                        } 
                });
            });
        } else {
            $(form).submit(function(e) {
                e.preventDefault();
                e.stopPropagation();
            });
        }
    }
</script>