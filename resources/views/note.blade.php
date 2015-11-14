<article class="panel panel-default">
    <div class="panel-heading">
        <a href="" title="Przejdź do widoku szczegółowego">Notatka</a>
    </div>
    <div class="panel-body">
      {!! $note->content !!}
    </div>
    <div class="panel-footer">
        <span data-toggle="tooltip" title="{{ $note->created_at }}">@icon('clock-o') {{ $note->created_at->diffForHumans() }}</span>
        <span class="pull-right hidden-print note-buttons">
             <a href="">@icon('check') @lang('note.buttons.done')</a>
             <a href="">@icon('pencil') @lang('note.buttons.edit')</a>
             <a href="" class="confirm-deletion">@icon('trash-o') @lang('note.buttons.remove')</a>
        </span>
    </div>
</article>