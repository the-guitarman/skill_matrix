var scrollToElement = function(element) {
    if (typeof(element) === 'string') {
        element = $(element);
    }

    if (element.length > 0) {
        $('html, body').animate({
            scrollTop: element.first().offset().top
        }, 1000);
    }
};

$(document).ready(function() {

    // Abfrage bei löschenden Formularen
    window.$(document).on('click', 'a[data-delete=confirm], button[data-delete=confirm]', function(e) {
        if (!confirm("Sind Sie sicher?")) {
            e.preventDefault();
            return false;
        }
    });

    // Submittet die übergeordnete Form - z.B. beim Logout
    window.$(document).on('click', 'a[data-submit=parent], button[data-submit=parent]', function(e) {
        e.preventDefault();
        $(this).parent('form').submit();
        return false;
    });
});