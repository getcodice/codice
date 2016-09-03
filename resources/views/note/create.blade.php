@extends('app')

@section('content')
<h2 class="page-heading primary">@lang('note.create.title')</h2>

<div class="codice-container">
{!! BootForm::open()->action(route('note.create')) !!}
    {!!
        BootForm::textarea(trans('note.labels.content'), 'content')
            ->class('form-control content')
            ->required()
            ->autofocus()
    !!}

    <div class="row">
        <div class="col-md-4">
            {!! form_picker('note.labels.expires_at', 'expires_at') !!}
        </div>
        <div class="col-md-4">
            {!! form_picker('reminder.services.email', 'reminder_email') !!}
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
            <option value="{{ $id }}">{{ $label }}</option>
        @endforeach
        </select>
    </div>

    {!! BootForm::submit(trans('note.create.submit'), 'btn-primary') !!}
{!! BootForm::close() !!}
</div>
@stop

@section('footer')
<script>
codiceDatetimePicker('#expires_at_picker');
codiceDatetimePicker('#reminder_email_picker');

codiceLabelSelector("#labels");
</script>
@stop
