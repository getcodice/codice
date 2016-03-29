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
            <span class="note-expired">
                @icon('warning') @lang('note.expires_at')
                @if ($note->expires_at->hour === 0 && $note->expires_at->minute === 0)
                    {{ $note->expires_at->format(trans('app.date')) }}
                @else
                    {{ $note->expires_at->format(trans('app.datetime')) }}
                @endif
            </span>
        @endif
        <span class="note-buttons">
            @if (!isset($single) || $single !== true)
            <a href="{!! route('note', ['id' => $note->id]) !!}">@icon('eye') <span class="text">@lang('note.buttons.details')</span></a>
            @endif
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