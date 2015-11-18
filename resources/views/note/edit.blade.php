@extends('app')

@section('content')
<h2 class="codice-header">@lang('note.edit.title')</h2>

{!! BootForm::open()->action(route('note.edit', ['id' => $note->id])) !!}
    <div class="row">
        <div class="col-md-9">
            {!! BootForm::textarea(trans('note.labels.content'), 'content', ['class' => 'content-textareaa'])->value($note->content) !!}
        </div>
        <div class="col-md-3 well">
            {!!
                BootForm::text(trans('note.labels.expires_at'), 'expires_at')
                    ->value(isset($note->expires_at) ? $note->expires_at->format('d.m.Y H:i') : null)
                    ->placeholder(trans('note.expires_at_placeholder'))
                    ->helpBlock(trans('note.expires_at_help'))
            !!}

            {!! BootForm::text(trans('note.labels.reminder_email'), 'reminder_email') !!}

            {!! BootForm::text(trans('note.labels.reminder_sms'), 'reminder_sms') !!}
        </div>
    </div>

    {!! BootForm::submit(trans('note.edit.submit'), 'btn-primary') !!}
{!! BootForm::close() !!}
@stop
