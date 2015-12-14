@extends('app')

@section('content')
<h2 class="page-heading">@lang('note.create.title')</h2>


{!! BootForm::open()->action(route('note.create')) !!}
    {!!
        BootForm::textarea(trans('note.labels.content'), 'content')
            ->class('form-control content')
            ->required()
            ->autofocus()
    !!}

    <div class="row">
        <div class="col-md-4">
            {!!
                BootForm::inputGroup(trans('note.labels.expires_at'), 'expires_at')
                    ->type('datetime')
                    ->afterAddon(icon('calendar'))
                    ->placeholder(datetime_placeholder('note.labels.expires_at'))
                    ->hideLabel();
            !!}
        </div>
        <div class="col-md-4">
            {!!
                BootForm::inputGroup(trans('note.labels.reminder_email'), 'reminder_email')
                    ->type('datetime')
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
            <option value="{{ $id }}">{{ $label }}</option>
        @endforeach
        </select>
    </div>

    {!! BootForm::submit(trans('note.create.submit'), 'btn-primary') !!}
{!! BootForm::close() !!}
@stop

@section('footer')
<script>
// Temporary hack
// @todo: remove after BootForms update
$('#expires_at').parent().addClass('date').attr('id', 'expires_at_picker');
$('#reminder_email').parent().addClass('date').attr('id', 'reminder_email_picker');

codiceDatetimePicker('#expires_at_picker');
codiceDatetimePicker('#reminder_email_picker');

codiceLabelSelector("#labels");
</script>
@stop
