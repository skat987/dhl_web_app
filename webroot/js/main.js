$(document).ready(function() {

    // method: fill the edit firm modal on firms index
    $('#modal').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget);
        var url = button.data('link');
        var id = button.data('id');
        var modal = $(this);

        if (id != null) {  
            $.ajax({
                type: 'GET',
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
            $.ajax({
                type: 'GET',
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
});