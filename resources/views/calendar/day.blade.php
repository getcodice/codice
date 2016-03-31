@extends('app')

@section('content')
    <h2 class="page-heading primary">
        @lang('calendar.title')
        <span class="page-heading-aside">
            {{$day }} {{ trans(Lang::has('calendar.months-genitive') ? "calendar.months-genitive.$month" : "calendar.months.$month") }} {{ $year }}
        </span>
    </h2>

    <div class="jscroll-container">
        @each('note.single', $notes, 'note', 'note.none')

        {!! $notes->render() !!}
    </div>
@stop

@section('footer')
<script>
codiceNotesPager();
</script>
@stop
