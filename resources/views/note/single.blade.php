<article class="panel panel-{{ $note->state }}">
    <div class="panel-heading">
        <a href="" title="Przejdź do widoku szczegółowego">{{ trans('note.heading.' . $note->state, ['expires' => isset($note->expires_at) ? $note->expires_at->format('d.m.Y H:i') : null]) }}</a>
    </div>
    <div class="panel-body">
      {!! $note->content !!}
    </div>
    <div class="panel-footer">
        <span data-toggle="tooltip" title="{{ $note->created_at->format('d.m.Y H:i') }}">@icon('clock-o') {{ $note->created_at->diffForHumans() }}</span>
        <span class="pull-right hidden-print note-buttons">
             <a href="{!! route('note.change', ['id' => $note->id]) !!}">
                @if ($note->status)
                    @icon('times') <span class="text">@lang('note.buttons.undone')</span>
                @else
                    @icon('check') <span class="text">@lang('note.buttons.done')</span>
                @endif
            </a>
             <a href="{!! route('note.edit', ['id' => $note->id]) !!}">@icon('pencil') <span class="text">@lang('note.buttons.edit')</span></a>
             <a href="{!! route('note.remove', ['id' => $note->id]) !!}" class="confirm-deletion">@icon('trash-o') <span class="text">@lang('note.buttons.remove')</span></a>
        </span>
    </div>
</article>