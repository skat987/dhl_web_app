/**
 * Description: script describing the front-end behavior of the application
 */

/**
 * Global variables
 */ 
const emailPattern = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
var modal, form;

/**
 * This run once the entire page is ready
 */
$(document).ready(function() {
    setUp();

    if (modal) {
        setUpModal(modal);
    }
});

function setUp() {
    modal = $('#modal');
}

function setUpModal(myModal) {
    var button, url;
    myModal.on('show.bs.modal', function(event) {
        button = $(event.relatedTarget);
        url = button.data('link');
        $.get({
            url: url,
            success: function(resp) {
                myModal.find('#modalContent').html(resp);
                form = myModal.find('form');
                setUpForm(form);
            },
            error: function(resp) {
                console.log('Error: the modal cannot be filled with content', resp);
                event.preventDefault();
            }
        });
    });
}

function setUpForm(myForm) {
    var controls = myForm.find('.form-control');
    var inputFile = myForm.find('.custom-file-input');
    if (inputFile) {
        inputFile.change(function() {
            inputFile[0].previousElementSibling.innerText = (inputFile[0].previousElementSibling.innerText == '') ? 'Sélectionnez un fichier' : inputFile[0].value;
            console.log('file selected', inputFile);
        });
    }
    controls.keyup(function() {
        checkControls($(this)[0]);
    });   
    controls.change(function() {
        checkControls($(this)[0]);
    });
    myForm.on('submit', function(event) {
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
