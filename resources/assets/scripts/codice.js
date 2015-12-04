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

$('#quickform_content').on('focus', function () {
    $(this).slideDown('slow', function() {
        $(this).attr('rows', '4');
    });
});

$('#quickform_content').on('focusout', function () {
    if ($(this).val() == '') {
        $(this).attr('rows', '1');
    }
});
