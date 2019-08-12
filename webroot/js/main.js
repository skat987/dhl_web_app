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
    // $.alert('Hello world', {
    //     type: 'success',
    //     position: ['top-left']
    // });
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
    }
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
        var btn = $(e.relatedTarget);
        $.get({
            url: btn.data('link'),
            success: function(resp) {
                if ($(btn).hasClass('add-customer-file-link')) {
                    $('#modal').children().first().addClass('modal-lg');
                }
                $('#modal').find('#modalContent').html(resp);
            },
            error: function(resp) {
                console.log('Error : the modal cannot be filled with content', resp);
                e.preventDefault();
                e.stopPropagation();
            }
        });
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

function setUpStorage(container, key) {
    $.get({
        url: container.data('link'),
        success: function(resp) {
            container.html(resp);
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
