function prepare() {
    $('span[data-toggle="tooltip"]').tooltip();
    $('.confirm-deletion').click(function() {
        if (!confirm("Czy na pewno skasować?")) {
            return false;
        }
    });
}

prepare();

var $navSearchLabel = $('.nav-search-label');
var $navSearchForm = $('.navbar-form');
$navSearchLabel.on('click', function () {
    $navSearchLabel.addClass('hidden');
    $navSearchForm.removeClass('hidden');
    $('.navbar-form input').focus();
});

$('.alert-fixed').on('click', function () {
    $(this).slideUp('slow', function () {
        $(this).remove();

    });
});

var $quickformControls = $('.quickform .row');
$('#quickform_content').on('focus', function () {
    $(this).slideDown('slow', function() {
        $(this).attr('rows', '4');
        $quickformControls.removeClass('sr-only');
    });
});

$('#quickform_content').on('focusout', function () {
    if ($(this).val() == '') {
        $(this).attr('rows', '1');
        $quickformControls.addClass('sr-only');
    }
});
