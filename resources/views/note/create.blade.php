@extends('app')

@section('content')
<h2 class="codice-header">@lang('note.create.title')</h2>

{!! BootForm::open(['route' => 'note.create']) !!}
    <div class="row">
        <div class="col-md-9">
            {!! BootForm::textarea(trans('note.labels.content'), 'content', ['class' => 'content-textareaa']) !!}
        </div>
        <div class="col-md-3 well">
            {!! BootForm::text(trans('note.labels.expires_at'), 'expires_at')->placeholder(trans('note.expires_at_placeholder'))->helpBlock(trans('note.expires_at_help')) !!}

            {!! BootForm::text(trans('note.labels.reminder_email'), 'reminder_email') !!}

            {!! BootForm::text(trans('note.labels.reminder_sms'), 'reminder_sms') !!}
        </div>
    </div>

    {!! BootForm::submit(trans('note.create.submit'), 'btn-primary') !!}
{!! BootForm::close() !!}
@stop
