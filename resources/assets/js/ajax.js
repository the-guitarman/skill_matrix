$(document).ready(function() {

    $(document).ajaxError(function(event, jqxhr, settings, thrownError ) {
        if (jqxhr.status === 419) {
            var errorMsg = 'Sie müssen den Portabilitätscheck neu starten, da Ihre Session abgelaufen ist. Jetzt neu starten?';
            if (confirm(errorMsg)) {
                window.location.reload();
            }
        }
    });

});