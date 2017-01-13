@extends('app')

@section('content')
    <h2 class="page-heading primary">
        @lang('search.title')
        <span class="page-heading-aside">“{{ $query }}”</span>
    </h2>


    <div class="jscroll-container">
        @each('note.single', $notes, 'note', 'search.none')

        {!! $notes->appends(['query' => Request::query('query')])->render() !!}
    </div>

    <div class="codice-container text-center">
        <p>@lang('search.tip', ['docs' => 'http://codice.eu/docs/searching', 'tip' => $tip])</p>
    </div>
@stop

@section('footer')
<script>
codiceNotesPager();
codiceLabelSelector("#quickform_labels");
</script>
@stop
