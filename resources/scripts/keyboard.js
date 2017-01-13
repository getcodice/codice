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
