/**
 * Description: script describing the front-end behavior of the application
 */

/**
 * Global variables
 */ 
const baseUrl = ''; // To use preview mode
const pages = ['/', '/admin/liste-des-societes', '/admin/liste-des-utilisateurs', '/esapce-client/:id'];
const emailPattern = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
const passwordPattern = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9]).{8,}$/;
const keyException = ['ArrowDown', 'ArrowLeft', 'ArrowRight', 'ArrowUp', 'End', 'Home', 'PageDown', 'PageUp'];
var page;

/**
 * This run once the entire page is ready
 */
$(function() {
    page = initializePage();
    setUpPage(page);
});

function initializePage() {
    var pathName = $(location).attr('pathname');
    var current = 3;
    $.each(pages, function(key, value) {
        if (pathName == (baseUrl + value)) {
            current = key;
        } 
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
    setUpForm();
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
    var container = $('#storageContent');
    var firmKey = container.data('firm');
    setUpAccessDropdown();
    setUpModal();
    setUpStorage(container, firmKey);
}

function setUpAccessDropdown() {
    var dropdown = $('#accessDropdown');
    dropdown.on('show.bs.dropdown', function(e) {
        $.get({
            url: $(e.relatedTarget).data('link'),
            success: function(resp) {
                dropdown.children().last().html(resp);
                setUpForm();
                $('#cancelBtn').click(function() {
                    dropdown.dropdown();
                    dropdown.children().last().empty();
                });
            },
            error: function(resp) {
                console.log('Error access form : ', resp);
                dropdown.children().last().html('<p>Error : The menu cannot be filled with content.</p>');
            }
        });
    });
}

function setUpModal() {
    var modal = $('#modal');
    modal.on('show.bs.modal', function(e) {
        $.get({
            url: $(e.relatedTarget).data('link'),
            success: function(resp) {
                modal.find('#modalContent').html(resp);
            },
            error: function(resp) {
                console.log('Error : the modal cannot be filled with content', resp);
                e.preventDefault();
                e.stopPropagation();
            }
        });
    });
    modal.on('shown.bs.modal', function() {
        setUpForm();
    });
    modal.on('hidden.bs.modal', function() {
        $(this).find('#modalContent').empty();
    });
}

function setUpNavBar(page) {
    $.each($('#navbar').find('.nav-link'), function(key, item) {
        if ((baseUrl + pages[page]) == $(item).attr('href')) {
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
        checks = input.checkValidity() && emailPattern.test(input.value);
    } else if (input.type == 'password') {
        checks = input.checkValidity() && passwordPattern.test(input.value);
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

function setUpForm() {
    var controls = $('form').find('.form-control');
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
    $('form').submit(function(e) { 
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
    var url, firmId;
    $('#searchDirectory_' + firmKey).keyup(function(e) {
        if ($(this).val() == '') {
            $('#optionsDirectories_' + firmKey).empty();
        }
        if (($.inArray(e.key, keyException) == -1) && ($(this).val() != '')) {
            firmId = $(this).data('firm');
            url = '/societe-' + firmId + '/dossiers/rechercher-' + $(this).val();
            $.get({
                url: url,
                dataType: 'json',
                success: function(resp) {
                    console.log('success', resp);
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
}
