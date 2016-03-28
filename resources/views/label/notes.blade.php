@extends('app')

@section('content')
    <h2 class="page-heading {{ config('labels.colors')[$label->color] }}">
        {!! trans('labels.notes.page-heading', ['label' => $label->name]) !!}
    </h2>

    @include('note.quickform')

    @each('note.single', $notes, 'note', 'note.none')
@stop

@section('footer')
<script>
codiceNotesPager();
codiceLabelSelector("#quickform_labels");
</script>
@stop
