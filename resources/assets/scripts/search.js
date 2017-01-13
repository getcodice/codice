var $navSearchLabel = $('.nav-search-label');
var $navSearchForm = $('.navbar-form');

$navSearchLabel.on('click', function () {
    $navSearchLabel.addClass('hidden');
    $navSearchForm.removeClass('hidden');
    $('.navbar-form input').focus();
});
