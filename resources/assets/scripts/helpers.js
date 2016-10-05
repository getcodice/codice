String.prototype.ucfirst = function () {
    return this.charAt(0).toUpperCase() + this.slice(1);
}

if (!Object.entries) {
    Object.entries = function objectValues(object) {
        return Object.keys(object).map(function (key) {
            return [key, object[key]];
        });
    };
}

function codiceDatetimePicker(domSelector) {
    flatpickr($(domSelector)[0], {
        allowInput: true,
        clickOpens: false,
        dateFormat: codiceLang.datetimeFormat,
        enableTime: true,
    });
}

function codiceLabelSelector(domSelector) {
    $(domSelector).select2({
        placeholder: codiceLang.selectLabels,
        tags: true,
        theme: "bootstrap",
    });
}

function codiceNotesPager() {
    $('.pager').hide();
    $('.jscroll-container').jscroll({
        loadingHtml: '<i class="fa fa-spinner fa-spin"></i>',
        padding: 10,
        nextSelector: '.pager a[rel="next"]',
        contentSelector: '.jscroll-container',
        callback: function () {
            $('.pager').hide();
            codicePrepare();
        }
    });
}
