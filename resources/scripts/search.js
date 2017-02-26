var $navSearchLabel = $('.nav-search-label');
var $navSearchForm = $('.navbar-form');

function codiceOpenSearch() {
    $navSearchLabel.addClass('hidden');
    $navSearchForm.removeClass('hidden');
    $('.navbar-form input').focus();
}

$navSearchLabel.on('click', 'codiceOpenSearch');
bindKeys('ctrl+shift+f', function() {codiceOpenSearch()}, {context: document});
