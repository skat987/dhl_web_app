/**
 * Description: script describing the front-end behavior of the application
 */

/**
 * Global variables
 */ 
const emailPattern = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
var modal, accessDropdown, firmStorage, firmsList;

/**
 * This run once the entire page is ready
 */
$(function() {
    setUp();
    if (modal.length) {
        setUpModal(modal);
    }
    if (accessDropdown.length) {
        setUpAccessForm(accessDropdown);
    }
    if (firmStorage.length) {
        setUpFirmStorage(firmStorage);
    }
    if (firmsList.length) {
        setUpFirmsList(firmsList);
    }
    // $('#drop').droppable({
    //     accept: '#drag',
    //     drop: function() {
    //         alert('Action terminée !');
    //     }
    // });
    // $('#drag').draggable({
    //     revert: 'invalid'
    // });
});

function setUp() {
    modal = $('#modal');
    accessDropdown = $('#accessDropdown');
    firmStorage = $('#storageContent');
    firmsList = $('#allFirms');
}

function setUpModal(myModal) {
    myModal.on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var url = button.data('link');
        $.get({
            url: url,
            success: function(resp) {
                myModal.find('#modalContent').html(resp);
                var form = myModal.find('form');
                setUpForm(form);
            },
            error: function(resp) {
                console.log('Error : the modal cannot be filled with content', resp);
                event.preventDefault();
                event.stopPropagation();
            }
        });
    });
}

function setUpForm(form) {
    var controls = form.find('.form-control');
    var inputFile = form.find('.custom-file-input');
    if (inputFile) {
        inputFile.change(function() {
            var values = inputFile[0].value.split('\\');
            inputFile[0].previousElementSibling.innerText = (inputFile[0].previousElementSibling.innerText == '') ? 'Sélectionnez un fichier' : values[2];
        });
    }
    controls.keyup(function() {
        checkControls($(this)[0]);
    });   
    controls.change(function() {
        checkControls($(this)[0]);
    });
    form.submit(function(event) {
        for (const control of controls) {
            control.parentElement.nextElementSibling.innerText = '';
            if (!control.checkValidity()) {
                control.classList.add('is-invalid');
                control.parentElement.nextElementSibling.innerText = control.validationMessage;
                control.parentElement.nextElementSibling.style.display = 'block';
                event.preventDefault();
                event.stopPropagation();
            } else {
                control.classList.remove('is-invalid');
                control.classList.add('is-valid');
            }
            if (control.type == 'email') {
                if (!emailPattern.test(control.value)) {   
                    control.classList.add('is-invalid');                
                    control.parentElement.nextElementSibling.innerText = (control.parentElement.nextElementSibling.innerText == '') ? 'Veuillez saisir une adresse électronique valide.' : control.parentElement.nextElementSibling.innerText;
                    control.parentElement.nextElementSibling.style.display = 'block';
                    event.preventDefault();
                    event.stopPropagation();
                } 
            }
            if (control.name == 'dirName') {
                if (control.value == '') {
                    control.classList.add('is-invalid');
                    control.parentElement.nextElementSibling.innerText = 'Veuillez saisir un nom de dossier';
                    control.parentElement.nextElementSibling.style.display = 'block';
                    event.preventDefault();
                    event.stopPropagation();
                } 
            }    
        }
    });
}

function checkControls(input) {
    var checks;
    if (input.type == 'email') {
        checks = input.checkValidity() && emailPattern.test(input.value);
    } else {
        checks = input.checkValidity();
    }
    if (checks) {
        input.parentElement.nextElementSibling.style.display = '';
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
    }
}

function setUpAccessForm(dropdown) {
    dropdown.on('show.bs.dropdown', function(event) {
        var button = $(event.relatedTarget);
        var url = button.data('link');
        var content = $('#editMyAccessForm');
        $.get({
            url: url,
            success: function(resp) {
                content.html(resp);
                var form = content.find('form');
                setUpForm(form);
            },
            error: function(resp) {
                console.log('Error access form : ', resp);
                content.html('<p>Error : The menu cannot be filled with content.</p>');
            }
        });
    });
}

function setUpFirmStorage(storage) {
    var url = storage.data('link');
    $.get({
        url: url,
        success: function(resp) {
            storage.html(resp);
            setUpStoragePagination(storage, $('#customerFilesPagination').find('.page-link'));
        },
        error: function(resp) {
            console.log('Error access storage : ', resp);
            storage.html('<p>Error : Cannot reach the storage content.</p>');
        }
    });
}

function setUpStoragePagination(container, buttons) {
    buttons.click(function(event) {
        event.preventDefault();
        event.stopPropagation();
        var url = $(this).attr('href');
        if (url) {
            $.get({
                url: url,
                success: function(resp) {
                    container.html(resp);
                    setUpStoragePagination(container, $('#customerFilesPagination').find('.page-link'));
                },
                error: function(resp) {
                    console.log('Error pagination : ', resp);
                }
            });
        }
    });
}

function setUpFirmsList(list) {
    var firms = list.find('.card');
    var firmsBtn = [];
    for (var i = 0; i < firms.length; i++) {
        firmsBtn[i] = firms.find('#firm_btn_' + i);
    }
    firmsBtn.forEach(function(btn) {
        btn.click(function() {
            var url = $(this).data('link');
            var target = $(this).data('target');
            var storage = firms.find(target);
            $.get({
                url: url,
                success: function(resp) {
                    storage.find('.card-body').html(resp);
                    setUpStoragePagination(storage.find('.card-body'), $('#customerFilesPagination').find('.page-link'));
                },
                error: function(resp) {
                    console.log('Error : cannot reach the storage content');
                    storage.html('<p>Error : Cannot reach the storage content</p>');
                }
            });
        });
    });
}
