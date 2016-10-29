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

function codiceConfirmPageClose(formSelector) {
    // Save form state on page load
    $(formSelector).data('serialized', $(formSelector).serialize());
    $(formSelector).data('submitting', false);

    $(formSelector).submit(function(e) {
        $(this).data('submitting', true);
    });

    // On page unload, check whether form state has changed
    $(window).bind('beforeunload', function(e) {
        var stateOnLoad = $(formSelector).data('serialized');
        var stateOnUnload = $(formSelector).serialize();

        // Do not show confirmation if form is actually being submitted
        if ($(formSelector).data('submitting') == true) {
            return undefined;
        }

        if (stateOnUnload != stateOnLoad) {
            return true;
        } else {
            e = null;
        }
    });
}
