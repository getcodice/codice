@extends('app')

@section('content')
<h2 class="page-heading primary">@lang('note.edit.title')</h2>

<div class="codice-container">
{!! BootForm::open()->action(route('note.edit', ['id' => $note->id]))->data('savable', 'true')->id('note_form') !!}
    {!!
        BootForm::textarea(trans('note.labels.content'), 'content')
            ->value($note->content_raw)
            ->class('form-control content')
            ->required()
            ->autofocus()
    !!}

    <div class="row">
        <div class="col-md-4">
            {!! form_picker('note.labels.expires_at', 'expires_at', $note->expires_at_fmt) !!}
        </div>
        <div class="col-md-4">
            {!! form_picker(
                'reminder.services.email',
                'reminder_email',
                isset($reminder_email->remind_at) ? $reminder_email->remind_at->format(trans('app.datetime')) : null
            ) !!}
        </div>
        <div class="col-md-4">
            {!!
                BootForm::inputGroup(trans('reminder.services.smsapi'), 'reminder_smsapi')
                    ->type('datetime')
                    ->afterAddon(icon('calendar'))
                    ->placeholder(datetime_placeholder('reminder.services.smsapi'))
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
</div>
@stop

@section('footer')
    @include('note.form-partial')
@stop
