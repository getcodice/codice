<script>
    @if ($wysiwyg)
    codiceEditor();
    @endif

    codiceDatetimePicker('#expires_at_picker');
    codiceDatetimePicker('#reminder_email_picker');

    codiceLabelSelector("#labels");

    codiceConfirmPageClose('#note_form');
</script>
