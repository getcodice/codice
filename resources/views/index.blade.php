@extends('app')

@section('content')
@include('note.quickform')

<div class="jscroll-container">
    @each('note.single', $notes, 'note', 'note.none')

    {!! $notes->render() !!}
</div>
@stop

@section('footer')
<script>
$('.pager').hide();
$('.jscroll-container').jscroll({
    loadingHtml: '<i class="fa fa-spinner fa-spin"></i>',
    padding: 10,
    nextSelector: '.pager a[rel="next"]',
    contentSelector: '.jscroll-container',
    callback: function () {
        $('.pager').hide();
    }
});

$("#quickform_labels").select2({
    placeholder: "@lang('note.labels.labels-select')",
    tags: true,
    theme: "bootstrap",
});
</script>
@stop
