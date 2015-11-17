@extends('app')

@section('content')
    @each('note.single', $notes, 'note')
@stop
