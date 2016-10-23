bootbox.setLocale(codiceLang.languageCode);

// Operations performed on each page of notes
function codicePrepare() {
    $('span[data-toggle="tooltip"], a[data-toggle="tooltip"]').tooltip();

    // Remove server-side confirmation of removal
    $('.note-buttons a[data-confirm="delete"]').attr('href', function (i, val) {
        return val.substr(0, val.length - 'confirm/'.length);
    })

    // Links with client-side confirmation
    $('a[data-confirm]').on('click', function (e) {
        e.preventDefault();

        var langKey = 'confirm' + $(this).data('confirm').ucfirst();
        var location = $(this).attr('href');

        bootbox.confirm(codiceLang[langKey], function(result) {
            if (result) {
                window.location.replace(location);
            }
        });
    });

    $savableForm = $('form[data-savable]');
    bindKeys('ctrl+s', function () {
        $savableForm.submit();
    }, {
        context: $savableForm,
    });
}

// Call codicePrepare() on first page load
$(function () {
    codicePrepare();
    codiceQuickForm();

    setTimeout(function () {
        // Nice trick, so we don't duplicate code
        $('.alert-fixed').trigger('click');
    }, 5000);
});

$('body').on('click', '.alert-fixed', function () {
    $(this).slideUp('slow', function () {
        $(this).remove();
    });
});
