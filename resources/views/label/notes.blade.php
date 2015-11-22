@extends('app')

@section('content')
    <h2><span class="label label-{{ $colors[$label->color] }}">@icon('tag') {{ $label->name }}</span></h2>

    @each('note.single', $notes, 'note')
@stop
