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

bootbox.setLocale(codiceLang.languageCode);

$(document).on('keydown', function (e) {
    if (!e.altKey && e.ctrlKey && e.keyCode === 83) {
        // On Windows, AltGr is represented as Alt+Ctrl, so we should check against
        // alt key, because user could have typed Alt+Ctrl+S which represents special
        // character on some keyboard layouts.

        e.preventDefault();

        return false;
    }
});

var keyBindingTable = {};
var keyNames = {
    backspace: 8,
    tab: 9,
    enter: 13,
    escape: 27,
    a: 65,
    b: 66,
    c: 67,
    d: 68,
    e: 69,
    f: 70,
    g: 71,
    h: 72,
    i: 73,
    j: 74,
    k: 75,
    l: 76,
    m: 77,
    n: 78,
    o: 79,
    p: 80,
    q: 81,
    r: 82,
    s: 83,
    t: 84,
    u: 85,
    v: 86,
    w: 87,
    x: 88,
    y: 89,
    z: 90,
};
var keyStack = [];

function handleKeyBinding(descriptor, e) {
    if (keyDescriptorFromEvent(e) !== descriptor) {
        return;
    }

    if (keyBindingTable.hasOwnProperty(descriptor)) {
        var entry = keyBindingTable[descriptor];

        entry.handler(e);
        e.preventDefault();

        return false;
    }
}

function findKeyName(keyCode) {
    return Object.entries(keyNames)
        .reduce(function (previous, entry) {
            if (entry[1] === keyCode) {
                return entry[0];
            }
            return previous;
        }, null);
}

function keyDescriptorFromEvent(e) {
    function when(predicate, string, otherwise) {
        return predicate ? string : otherwise;
    }

    var keyName = findKeyName(e.keyCode);

    return when(e.ctrlKey, 'ctrl+', '') +
           when(e.shiftKey, 'shift+', '') +
           when(e.altKey, 'alt+', '') +
           when(keyName, keyName, '#' + e.keyCode);
}

// The descriptor must have the following form: ["ctrl+"] ["shift+"] ["alt+"]
function bindKeys(descriptor, handler, options) {
    options = $.extend({}, {
        context: document,
    }, options);

    keyBindingTable[descriptor] = {
        handler: handler,
        context: options.context,
    };

    $(options.context).on('keyup', handleKeyBinding.bind(null, descriptor));
}

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

$('.alert-fixed').on('click', function () {
    $(this).slideUp('slow', function () {
        $(this).remove();
    });
});

function codiceQuickForm() {
    var $form = $('.quickform form');
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

    bindKeys('ctrl+s', function () {
        $form.submit();
    }, {
        context: $form,
    });
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
