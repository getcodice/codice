<?php $state = $note->state ?>
<article class="note note-{{ $state }} {{ isset($single) && $single === true ? 'note-single' : '' }}">
    <div class="note-body">
        @if (count($note->labels))
        <ul class="note-labels">
            @foreach ($note->labels as $label)
            <li><a href="{!! route('label', ['id' => $label->id]) !!}" class="label label-{{ config('labels.colors.' . $label->color) }}">{{ $label->name }}</a></li>
            @endforeach
        </ul>
        @endif
        @if (count($note->labels))
        <div class="note-content has-labels">
        @endif
        {!! $note->content !!}
        @if (count($note->labels))
        </div>
        @endif
    </div>
    <footer class="note-footer">
        @if ($note->updated_at > $note->created_at)
        <span data-toggle="tooltip" title="
            {{ $note->created_at->format(trans('app.datetime')) }}
            (@lang('note.updated') {{ $note->updated_at->format(trans('app.datetime')) }})
        ">
        @else
        <span data-toggle="tooltip" title="{{ $note->created_at->format(trans('app.datetime')) }}">
        @endif
            @icon('clock-o') {{ $note->created_at->diffForHumans() }}
        </span>
        @if ($state != 'default' && $state != 'success')
            @icon('warning') Termin wykonania: 12.02.2016
        @endif
        <span class="note-buttons">
             <a href="{!! route('note.change', ['id' => $note->id]) !!}">
                @if ($note->status)
                    @icon('times') <span class="text">@lang('note.buttons.undone')</span>
                @else
                    @icon('check') <span class="text">@lang('note.buttons.done')</span>
                @endif
            </a>
             <a href="{!! route('note.edit', ['id' => $note->id]) !!}">@icon('pencil') <span class="text">@lang('note.buttons.edit')</span></a>
             <a href="{!! route('note.remove', ['id' => $note->id]) !!}" data-confirm="delete">@icon('trash-o') <span class="text">@lang('note.buttons.remove')</span></a>
        </span>
    </footer>
</article>