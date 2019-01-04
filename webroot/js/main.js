/**
 * TODO: Mettre en place une méthode pour récupérer l'item actif du menu afin d'initialiser la page avec les bons composants 
 */

$(document).ready(function() {
    setUpFirmsIndex();
});

function setUpFirmsIndex() {
    var modal = $('#modal');
    if (modal) {
        showModal(modal);
        // showFileSelected(modal);
        // setDirsSelect(modal);
        // createDir(modal);
    }
}

function showModal(modal) {
    modal.on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var url = button.data('link');
        var id = button.data('firm');
        if (id != null) {
            $.get({
                url: url,
                data: 'id=' + id,
                success: function(data) {
                    modal.find('#modalContent').html(data);
                },
                error: function(data){
                    console.log('error ', data);
                }
            });
        } else {
            $.get({
                url: url,
                success: function(data) {
                    modal.find('#modalContent').html(data);
                },
                error: function(data){
                    console.log('error ', data);
                }
            });
        }
    });
}

function showFileSelected(modal) {
    // modal.on('shown.bs.modal', function() {
    //     var fileInput = modal.find('#fileSelected');
    //     fileInput.bind('change', function() {
    //         console.log('file input', $(this).);
    //     });
    // });
}

function setDirsSelect(modal) {
    modal.on('shown.bs.modal', function() {
        var firmsSelect = modal.find('#firmsSelect');
        firmsSelect.bind('change', function() {
            getDirs(firmsSelect.val());
        });
    });
}

function getDirs(firmId) {
    var dirsSelect = $('#modal').find('#dirsSelect');
    if (dirsSelect) {
        if (firmId) {
            $.get({
                url: '/customer-files/get-firm-directories/' + firmId,
                data: 'id=' + firmId,
                dataType: 'json',
                success: function(data){
                    if (data.length > 0){
                        dirsSelect.empty().append('<option value=' + null + '>Sélectionner un dossier</option>');
                        $.each(data, function(key, value){
                            dirsSelect.append('<option value="' + value + '">' + value + '</option>');
                        });
                    } else {
                        dirsSelect.empty().append('<option value=' + null + '>Pas de dossier</option>');
                    }
                },
                error: function(data){
                    console.log('error', data);
                }
            });
        }
    }
}

function createDir(modal) {
    modal.on('shown.bs.modal', function() {
        var button = modal.find('#newDirBtn');
        button.on('click', function() {
            var newDir = modal.find('#newDirName').val();
            var firmId = modal.find('#firmsSelect').val();
            var dirsSelect = modal.find('#dirsSelect');
            if (firmId) {
                $.post({
                    url: '/customer-files/create-directory',
                    data: {
                        firmId: firmId,
                        newDir: newDir
                    },
                    dataType: 'json',
                    beforeSend: function (xhr) { 
                        xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                    },  
                    success: function(data) {
                        modal.find('#newDirResult').html(data.message);
                        dirsSelect.append('<option value="' + data.value + '" selected>' + data.value + '</option>');
                    },
                    error: function(data) {
                        console.log('error', data);
                    }
                });
            }
        });
    });
}
