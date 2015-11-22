@extends('app')

@section('content')
<h2 class="codice-header">@lang('labels.edit.title')</h2>

{!! BootForm::open()->action(route('label.edit', ['id' => $label->id])) !!}
    {!! BootForm::text(trans('labels.labels.name'), 'name')->value($label->name) !!}

    <div class="form-group">
        <span class="form-group-title">@lang('labels.labels.color')</span>
        <div class="btn-group" data-toggle="buttons">
            @foreach (config('labels.colors') as $id => $color)
            <label class="btn btn-{{ $color }} {{ $id == $label->color ? 'active' : '' }}">
                <input type="radio" name="color" value="{{ $id }}" {{ $id == $label->color ? 'checked' : '' }}>
                @lang('labels.colors.' . $id)
            </label>
            @endforeach
        </div>
    </div>

    {!! BootForm::submit(trans('labels.edit.submit'), 'btn-primary') !!}
{!! BootForm::close() !!}
@stop
