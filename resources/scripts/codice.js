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
