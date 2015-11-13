@extends('app')

@section('content')
    @each('note', $notes, 'note')
@stop
