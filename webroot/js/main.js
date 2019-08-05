/**
 * Description: script describing the front-end behavior of the application
 */

/**
 * Global variables
 */ 
const _PAGES = ['/', '/admin/liste-des-societes', '/admin/liste-des-utilisateurs', '/esapce-client/:id'];
const _DEFAULT_PAGE_INDEX = 3;
const _PATTERNS = {
    email: /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/,
    password: /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$/ 
}
const _KEY_EXCEPTION = ['ArrowDown', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'End', 'Home', 'PageDown', 'PageUp', 'Enter', 'Tab'];
var page;

/**
 * This run once the entire page is ready
 */
$(function() {
    page = initializePage();
    setUpPage(page);
});

function initializePage() {
    var current = _DEFAULT_PAGE_INDEX;
    $.each(_PAGES, function(key, value) { 
        current = ($(location).attr('pathname') == value) ? key : current;
    });
    return current;
}

function setUpPage(index) {
    switch (index) {
        case 1:
            setUpAllFirms(index);
            break;
        case 2:
            setUpAllUsers(index);
            break;
        case 3:
            setUpViewFirm();
            break;
        default:
            setUpLoginPage();
    }
}

function setUpLoginPage() {
    setUpForm($('form'));
}

function setUpAllFirms(page) {
    var firms = $('#allFirms').find('.card');
    var firmsBtn = [];
    var lastCollapse, lastKey;
    setUpNavBar(page);
    setUpAccessDropdown();
    setUpModal();
    $.each(firms, function(key, firm) {
        firmsBtn[key] = $(firm).find('#firm_btn_' + key);
    });
    if (firmsBtn.length) {
        $.each(firmsBtn, function(key, btn) {
            btn.click(function(e) {
                var collapse = $(firms[key]).find($(this).data('target'));
                if (!lastCollapse) {  
                    setUpStorage(collapse.find('#storageContent_' + key), key);
                    collapse.collapse('show');
                    lastCollapse = collapse;
                    lastKey = key;
                } else {                
                    if (lastCollapse.attr('id') == collapse.attr('id')) {
                        lastCollapse.collapse('hide');
                        lastCollapse.find('#storageContent_' + lastKey).empty();
                        lastCollapse = null;                        
                    } else {
                        setUpStorage(collapse.find('#storageContent_' + key), key);
                        collapse.collapse('show');
                        lastCollapse.find('#storageContent_' + lastKey).empty();
                        lastCollapse = collapse;
                        lastKey = key;
                    }
                }               
            });
        });
    }
}

function setUpAllUsers(page) {
    setUpNavBar(page);
    setUpAccessDropdown();
    setUpModal();
}

function setUpViewFirm() {
    setUpAccessDropdown();
    setUpModal();
    setUpStorage($('#storageContent'), $('#storageContent').data('firm'));
}

function setUpAccessDropdown() {
    $('#accessDropdown').on('show.bs.dropdown', function(e) {
        $.get({
            url: $(e.relatedTarget).data('link'),
            success: function(resp) {
                $('#accessDropdown').children().last().html(resp);
                setUpAccessForm($('#accessDropdown').children().last().find('form'));
                $('#cancelBtn').click(function() {
                    $('#accessDropdown').dropdown();
                });
            },
            error: function(resp) {
                console.log('Error access form : ', resp);
                $('#accessDropdown').children().last().html('<p>Error : The menu cannot be filled with content.</p>');
            }
        });
    });
    $('#accessDropdown').on('hide.bs.dropdown', function() {
        $(this).children().last().empty();
    });
}

function setUpModal() {
    $('#modal').on('show.bs.modal', function(e) {
        $.get({
            url: $(e.relatedTarget).data('link'),
            success: function(resp) {
                $('#modal').find('#modalContent').html(resp);
            },
            error: function(resp) {
                console.log('Error : the modal cannot be filled with content', resp);
                e.preventDefault();
                e.stopPropagation();
            }
        });
    });
    $('#modal').on('shown.bs.modal', function() {
        setUpForm($(this).find('form'));
    });
    $('#modal').on('hidden.bs.modal', function() {
        $(this).find('#modalContent').empty();
    });
}

function setUpNavBar(page) {
    $.each($('#navbar').find('.nav-link'), function(key, item) {
        if (_PAGES[page] == $(item).attr('href')) {
            $(item).parent().addClass('active');
            $(item).append('<span class="sr-only">(current)</span>');
        }
    });
}

function setValidation(controls) {
    var isValid = true;
    var isFile = false;
    $.each(controls, function(key, control) {
        if (!checkControls(control)) {
            isValid = false;
        }
        if (control.type == 'file') {
            if (control.value != '') {
                isFile = true;
            }
            if (!isFile) {
                isValid = false;
                $.each(controls, function(key, control) {
                    if (control.type == 'file') {
                        control.classList.remove('is-valid');
                        $(controls[1]).attr('required', 'required');
                        checkControls(controls[1]);
                        $(controls[1]).prev().addClass('is-invalid');
                    }
                });
            }
        }
    });
    return isValid;
}

function checkControls(input) {
    var checks;
    if (input.type == 'email') {
        checks = input.checkValidity() && _PATTERNS.email.test(input.value);
    } else if (input.type == 'password') {
        checks = input.checkValidity() && _PATTERNS.password.test(input.value);
    } else {
        checks = input.checkValidity();
    }
    if (checks) {
        input.parentElement.nextElementSibling.style.display = '';
        input.classList.remove('is-invalid');
        input.classList.add('is-valid');
    } else {
        input.classList.remove('is-valid');
        input.classList.add('is-invalid');
        input.parentElement.nextElementSibling.innerHTML = '<small>' + input.validationMessage + '</small>';
        input.parentElement.nextElementSibling.style.display = 'block';
    }
    return checks;
}

function setUpForm(form) {
    var controls = form.find('.form-control');
    controls.change(function() {
        if ($(this).attr('type') == 'file') {
            var fileName = $(this).val().split('\\')[2];
            if (fileName != '') {
                $(this).prev().removeClass('is-invalid');
                $(this).prev().addClass('is-valid');
            }
            $(this).prev().text(fileName);
        }
        setValidation($(this));
    });
    form.submit(function(e) { 
        if (!setValidation(controls)) {
            e.preventDefault();
            e.stopPropagation();
        }
    });
}

function setUpStorage(container, key) {
    $.get({
        url: container.data('link'),
        success: function(resp) {
            container.html(resp);
            SetUpCustomerFilesActions();
            setUpSearchDirectory(container, key);
            setUpStoragePagination(container, key, $('#storagePagination').find('.page-link'));
        },
        error: function(resp) {
            console.log('Error access storage : ', resp);
            container.html('<p>Error : Cannot reach the storage content.</p>');
        }
    });
}

function setUpStoragePagination(container, key, buttons) {
    buttons.click(function(event) {
        event.preventDefault();
        event.stopPropagation();
        $.get({
            url: $(this).attr('href'),
            success: function(resp) {
                container.html(resp);
                setUpSearchDirectory(container, key);
                setUpStoragePagination(container, key, $('#storagePagination').find('.page-link'));
            },
            error: function(resp) {
                console.log('Error pagination : ', resp);
                container.html('<p>Error : Cannot reach the storage content.</p>');
            }
        });
    });
}

function setUpSearchDirectory(container, firmKey) {
    var url;
    $('#searchDirectory_' + firmKey).keyup(function(e) {
        if ($(this).val() == '') {
            $('#optionsDirectories_' + firmKey).empty();
        }
        if (($.inArray(e.key, _KEY_EXCEPTION) < 0) && ($(this).val() != '')) {
            url = '/societe-' + $(this).data('firm') + '/dossiers/rechercher-' + $(this).val();
            $.get({
                url: url,
                dataType: 'json',
                success: function(resp) {
                    $('#optionsDirectories_' + firmKey).empty();
                    $.each(resp, function(key, r) {
                        $('#optionsDirectories_' + firmKey).append(
                            '<option value="' + r.name + '">'
                        );
                    });
                },
                error: function(resp) {
                    console.log('Error search directories', resp);
                }
            });
        }        
    });
    $('#searchForm_' + firmKey).submit(function(e) {
        e.preventDefault();
        if ($('#searchDirectory_' + firmKey).val() != '') {
            url = '/societe-' + $('#searchDirectory_' + firmKey).data('firm') + '/liste-des-documents/' + $('#searchDirectory_' + firmKey).val();
        } else {
            url = '/societe-' + $('#searchDirectory_' + firmKey).data('firm') + '/liste-des-documents/all';
        }
        $.get({
            url: url,
            success: function(resp) {
                container.html(resp);
                setUpSearchDirectory(container, firmKey);
                setUpStoragePagination(container, firmKey, $('#storagePagination').find('.page-link'));
            },
            error: function(resp) {
                console.log('Error filtering directories list', resp);
                container.html('<p>Error : Cannot reach the storage content.</p>');
            }
        });
    });
    $('#resetSearch_' + firmKey).click(function() {
        $.get({
            url: $(this).data('link'),
            success: function(resp) {
                container.html(resp);
                setUpSearchDirectory(container, firmKey);
                setUpStoragePagination(container, firmKey, $('#storagePagination').find('.page-link'));
            },
            error: function(resp) {
                console.log('Error filtering directories list', resp);
                container.html('<p>Error : Cannot reach the storage content.</p>');
            }
        });
    });
    $('#dgf_' + firmKey).click(function() {
        $.get({
            url: $(this).data('link'),
            success: function(resp) {
                container.html(resp);
                setUpSearchDirectory(container, firmKey);
                setUpStoragePagination(container, firmKey, $('#storagePagination').find('.page-link'));
            },
            error: function(resp) {
                console.log('Error filtering directories list', resp);
                container.html('<p>Error : Cannot reach the storage content.</p>');
            }
        });
    });
    $('#express_' + firmKey).click(function() {
        $.get({
            url: $(this).data('link'),
            success: function(resp) {
                container.html(resp);
                setUpSearchDirectory(container, firmKey);
                setUpStoragePagination(container, firmKey, $('#storagePagination').find('.page-link'));
            },
            error: function(resp) {
                console.log('Error filtering directories list', resp);
                container.html('<p>Error : Cannot reach the storage content.</p>');
            }
        });
    });
}

function setUpAccessForm(form) {
    var email = form.find('#email');
    var oldPass = form.find('#oldpassword');
    var newPass = form.find('#newpassword');
    email.change(function() {
        checkControls($(this)[0]);
    });
    oldPass.keyup(function(e) {
        if (($.inArray(e.key, _KEY_EXCEPTION) < 0) && ($(this).val() != '')) {
            $.post({
                url: '/verification-pass',
                data: {
                    pass: $(this).val()
                },
                dataType: 'json',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-CSRF-Token', $('[name="_csrfToken"]').val());
                },
                success: function(resp) {
                    if (resp) {
                        $(newPass).attr('disabled', false);
                    }
                },
                error: function(resp) {
                    console.log('error', resp);
                }
            });
        }
    });
    newPass.change(function() {
        checkControls($(this)[0]);
    });
    form.submit(function(e) {
        if (!checkControls(email[0])) {
            e.preventDefault();
            e.stopPropagation();
        }
        if (!$(newPass).attr('disabled')) {   
            if (!checkControls(newPass[0])) {  
                e.preventDefault();
                e.stopPropagation();
            } 
        };
    });
}

function SetUpCustomerFilesActions() {
    $('.delete-customer-file-link').click(function(e) {
        if (!e.originalEvent.returnValue) {
            e.preventDefault();
            e.stopPropagation();
        } else {
            $(this).parent().submit(function(e) {
                e.preventDefault();
                e.stopPropagation();
                var form = $(this);
                $.post({
                    url: $(form).prop('action'),
                    dataType: 'json',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-CSRF-Token', $(form).find('[name="_csrfToken"]').val());
                    },
                    success: function(resp) {
                        if (resp.result == 'success') {
                            $(form).parent().removeClass('d-flex').css('display', 'none');
                            $('#filesCount-firm-' + resp.firmId).text(resp.filesCount);
                            $.alert(resp.text, {
                                autoClose: true,
                                closeTime: 3000,
                                type: 'success',
                                position: ['bottom-right']
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
        }
    });
}
