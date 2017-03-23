function codiceShowAlert(message, type) {
    type = type || 'info';

    // Remove alert if there is any
    $('.alert.alert-fixed').remove();

    // Add new alert
    $('body').prepend($([
        '<div class="alert alert-fixed alert-' + type + '">',
        '<p>' + message + '</p>',
        '</div>',
    ].join('')));

    // Make sure alert is cleared normally
    codiceClearAlerts();
}

function codiceClearAlerts() {
    setTimeout(function () {
        $('.alert-fixed').trigger('click');
    }, 5000);
}

$('body').on('click', '.alert-fixed', function () {
    $(this).slideUp('slow', function () {
        $(this).remove();
    });
});
