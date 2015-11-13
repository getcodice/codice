<article class="panel panel-default">
    <div class="panel-heading">
        <a href="" title="Przejdź do widoku szczegółowego">Notatka</a>
    </div>
    <div class="panel-body">
      {!! $note->content !!}
    </div>
    <div class="panel-footer">
        <span data-toggle="tooltip" title="{{ $note->created_at }}"><span class="fa fa-clock-o"></span> {{ $note->created_at->diffForHumans() }}</span>
        <span class="pull-right hidden-print note-buttons">
             <a href=""><span class="fa fa-check"></span> Wykonane</a>
             <a href=""><span class="fa fa-pencil"></span> Edytuj</a>
             <a href="" class="confirm-deletion"><span class="fa fa-trash-o"></span> Usuń</a>
        </span>
    </div>
</article>