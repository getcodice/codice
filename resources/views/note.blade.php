<article class="panel panel-default">
    <div class="panel-heading">
        <a href="" title="Przejdź do widoku szczegółowego">Notatka</a>
    </div>
    <div class="panel-body">
      {!! $note->content !!}
    </div>
    <div class="panel-footer">
        <span data-toggle="tooltip" title="{{ $note->created_at->format('d.m.Y H:i') }}">@icon('clock-o') {{ $note->created_at->diffForHumans() }}</span>
        <span class="pull-right hidden-print note-buttons">
             <a href="">@icon('check') <span class="text">@lang('note.buttons.done')</span></a>
             <a href="">@icon('pencil') <span class="text">@lang('note.buttons.edit')</span></a>
             <a href="" class="confirm-deletion">@icon('trash-o') <span class="text">@lang('note.buttons.remove')</span></a>
        </span>
    </div>
</article>