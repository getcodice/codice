@extends('app')

@section('content')
<h2 class="page-heading">@lang('labels.create.title')</h2>

{!! BootForm::open()->action(route('label.create')) !!}
    {!! BootForm::text(trans('labels.labels.name'), 'name') !!}

    <div class="form-group">
        <span class="form-group-title">@lang('labels.labels.color')</span>
        <div class="btn-group" data-toggle="buttons">
            @foreach (config('labels.colors') as $id => $color)
            <label class="btn btn-{{ $color }} {{ $id == 1 ? 'active' : '' }}">
                <input type="radio" name="color" value="{{ $id }}" {{ $id == 1 ? 'checked' : '' }}>
                @lang('labels.colors.' . $id)
            </label>
            @endforeach
        </div>
    </div>

    {!! BootForm::submit(trans('labels.create.submit'), 'btn-primary') !!}
{!! BootForm::close() !!}
@stop
