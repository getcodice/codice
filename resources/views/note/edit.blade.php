@extends('app')

@section('content')
<h2 class="page-heading">@lang('note.edit.title')</h2>

{!! BootForm::open()->action(route('note.edit', ['id' => $note->id])) !!}
    {!!
        BootForm::textarea(trans('note.labels.content'), 'content')
            ->value($note->content_raw)
            ->class('form-control content')
            ->required()
            ->autofocus()
    !!}

    <div class="row">
        <div class="col-md-4">
            {!!
                BootForm::inputGroup(trans('note.labels.expires_at'), 'expires_at')
                    ->type('datetime')
                    ->value($note->expires_at_fmt)
                    ->afterAddon(icon('calendar'))
                    ->placeholder(datetime_placeholder('note.labels.expires_at'))
                    ->hideLabel();
            !!}
        </div>
        <div class="col-md-4">
            {!!
                BootForm::inputGroup(trans('note.labels.reminder_email'), 'reminder_email')
                    ->type('datetime')
                    ->value(isset($reminder_email->remind_at) ? $reminder_email->remind_at->format(trans('app.datetime')) : null)
                    ->afterAddon(icon('calendar'))
                    ->placeholder(datetime_placeholder('note.labels.reminder_email'))
                    ->hideLabel();
            !!}
        </div>
        <div class="col-md-4">
            {!!
                BootForm::inputGroup(trans('note.labels.reminder_smsapi'), 'reminder_smsapi')
                    ->type('datetime')
                    ->afterAddon(icon('calendar'))
                    ->placeholder(datetime_placeholder('note.labels.reminder_smsapi'))
                    ->hideLabel()
                    ->disabled();
            !!}
        </div>
    </div>

    <div class="form-group">
        <label class="control-label" for="labels">@lang('note.labels.labels')</label>
        <select name="labels[]" class="form-control" id="labels" multiple>
        @foreach ($labels as $id => $label)
            <option value="{{ $id }}" {{ in_array($id, $note_labels) ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
        </select>
    </div>

    {!! BootForm::submit(trans('note.edit.submit'), 'btn-primary') !!}
{!! BootForm::close() !!}
@stop

@section('footer')
<script>
// Temporary hack
// @todo: remove after BootForms update
$('#expires_at').parent().addClass('date').attr('id', 'expires_at_picker');
$('#reminder_email').parent().addClass('date').attr('id', 'reminder_email_picker');

$('#expires_at_picker').datetimepicker({
    locale: '{{ Auth::user()->options['language'] }}',
    icons: {
        time: 'fa fa-clock-o',
        date: 'fa fa-calendar',
        up: 'fa fa-arrow-up',
        down: 'fa fa-arrow-down',
        previous: 'fa fa-arrow-left',
        next: 'fa fa-arrow-right',
        clear: 'fa fa-trash-o',
        close: 'fa fa-times'
    }
});
$('#reminder_email_picker').datetimepicker({
    locale: '{{ Auth::user()->options['language'] }}',
    icons: {
        time: 'fa fa-clock-o',
        date: 'fa fa-calendar',
        up: 'fa fa-arrow-up',
        down: 'fa fa-arrow-down',
        previous: 'fa fa-arrow-left',
        next: 'fa fa-arrow-right',
        clear: 'fa fa-trash-o',
        close: 'fa fa-times'
    }
});

$("#labels").select2({
    placeholder: "@lang('note.labels.labels-select')",
    tags: true,
    theme: "bootstrap",
});
</script>
@stop
