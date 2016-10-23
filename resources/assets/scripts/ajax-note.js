$('.container').on('click', '.note-buttons a.note-change', function(e) {
    var $note = $(this).closest('article.note');
    var noteId = $note.attr('id').split('-')[1];

    e.preventDefault();

    codiceAjax('note/' + noteId + '/mark', {}, function(response) {
        $note.replaceWith(response['data']);

        codiceAddAlert(response['message']);
    });
});
