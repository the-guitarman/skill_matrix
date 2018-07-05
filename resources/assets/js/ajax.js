$(document).ready(function() {

    $(document).ajaxError(function(event, jqxhr, settings, thrownError ) {
        if (jqxhr.status === 419) {
            var errorMsg = 'Sie müssen den Portabilitätscheck neu starten, da Ihre Session abgelaufen ist. Jetzt neu starten?';
            if (confirm(errorMsg)) {
                window.location.reload();
            }
        }
    });

    $(document).on('show.bs.modal', '#ajax-modal', function(event) {
        var clickedLink = $(event.relatedTarget);
        var modal = $(this);
        modal.find('.modal-body').html('<div class="text-center"><i class="fa fa-spinner"></i> Loading ...</div>');

        $.ajax({
            type: 'GET',
            url: clickedLink.attr('href'),
            success: function(data, textStatus, jqXHR) {
                modal.find('.modal-body').html(data);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                modal.find('.modal-body').html('<div class="alert alert-danger text-center">Entschuldigung, da ist etwas schief gegangen.<div>');
            }
        });
    });

});