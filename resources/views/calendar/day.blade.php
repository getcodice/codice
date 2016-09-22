@extends('app')

@section('content')
    <h2 class="page-heading primary">@lang('calendar.title') <span class="page-heading-aside">{{ $title }}</span></h2>

    {{ $quickform }}

    <div class="jscroll-container">
        @each('note.single', $notes, 'note', 'note.none')

        {!! $notes->render() !!}
    </div>
@stop

@section('footer')
<script>
codiceNotesPager();
codiceLabelSelector('#quickform_labels');
</script>
@stop
