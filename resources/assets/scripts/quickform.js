function codiceQuickForm() {
    var $quickformControls = $('.quickform .row');
    var $quickformContent = $('#quickform_content');

    $quickformContent.on('focus', function () {
        $(this).slideDown('slow', function () {
            $quickformContent.attr('rows', '4');
            $quickformControls.removeClass('sr-only');
        });
    });

    var $quickformCollapseButton = $(
        '<button type="button" class="btn btn-default quickform-collapse">' +
        '<span class="sr-only">' +
        codiceLang.collapse +
        '</span>' +
        '<span class="fa fa-angle-double-up aria-hidden="true"></span>' +
        '</button>')
        .insertBefore($('.quickform-submit').removeClass('btn-block'))
        .click(function () {
            $quickformControls.addClass('sr-only');
            $quickformContent.attr('rows', 1);
        });
}
