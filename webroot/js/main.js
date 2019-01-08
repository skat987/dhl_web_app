/**
 * Description: script describing the front-end behavior of the application
 */

/**
 * Global variables
 */ 
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
        console.log('url= ', url);
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
    //var submit = myForm.find('')
    var controls = myForm.find('.form-control');
    myForm.on('submit', function(event) {
        console.log('Submit', event);
        event.preventDefault();
    });
}
