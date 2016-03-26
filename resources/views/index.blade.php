@extends('app')

@section('content')
@include('note.quickform')

<div class="notes-container">
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
