@extends('app')

@section('content')
<h2 class="page-heading info">@lang('labels.edit.title')</h2>

<div class="codice-container">
{!! BootForm::open()->action(route('label.edit', ['id' => $label->id])) !!}
    {!! BootForm::text(trans('labels.labels.name'), 'name')->value($label->name)->required()->autofocus() !!}

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
</div>
@stop
