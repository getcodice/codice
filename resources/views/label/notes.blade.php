@extends('app')

@section('content')
    <h2 class="page-heading {{ config('labels.colors')[$label->color] }}">
        {!! trans('labels.notes.page-heading', ['label' => $label->name]) !!}
    </h2>

    {{ $quickform }}

    <div class="jscroll-container">
        @each('note.single', $notes, 'note', 'note.none')

        {!! $notes->render() !!}
    </div>
@stop

@section('footer')
<script>
codiceNotesPager();
codiceLabelSelector("#quickform_labels");
</script>
@stop
