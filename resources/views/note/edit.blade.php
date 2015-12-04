@extends('app')

@section('content')
<h2 class="codice-header">@lang('note.edit.title')</h2>

{!! BootForm::open()->action(route('note.edit', ['id' => $note->id])) !!}
    <div class="row">
        <div class="col-md-9">
            {!! BootForm::textarea(trans('note.labels.content'), 'content')->value($note->content_raw) !!}
        </div>
        <div class="col-md-3 well">
            {!!
                BootForm::text(trans('note.labels.expires_at'), 'expires_at')
                    ->value(isset($note->expires_at) ? $note->expires_at->format('d.m.Y H:i') : null)
                    ->placeholder(trans('note.datetime-placeholder'))
                    ->helpBlock(trans('note.optional-field'))
            !!}

            {!!
                BootForm::text(trans('note.labels.reminder_email'), 'reminder_email')
                    ->value(isset($reminder_email->remind_at) ? $reminder_email->remind_at->format('d.m.Y H:i') : null)
                    ->placeholder(trans('note.datetime-placeholder'))
                    ->helpBlock(trans('note.optional-field'))
            !!}

            {!! BootForm::text(trans('note.labels.reminder_smsapi'), 'reminder_smsapi')->placeholder(trans('note.datetime-placeholder'))->helpBlock(trans('note.optional-field'))->disabled() !!}
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
$("#labels").select2({
    placeholder: "@lang('note.labels.labels-select')",
});
</script>
@stop
