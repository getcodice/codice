<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>{{ isset($title) ? $title . ' • ' : '' }}Codice</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700,400italic&amp;subset=latin,latin-ext">
    <link rel="stylesheet" href="{!! asset('assets/css/codice.css') !!}">
</head>
<body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
        <div class="container-fluid">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navigation">
                    <span class="sr-only">@lang('app.menu.toggle')</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{!! route('index') !!}"><img alt="Codice" src="{!! asset('assets/images/brand.png') !!}"></a>
            </div>
            <div class="collapse navbar-collapse" id="navigation">
                <ul class="nav navbar-nav">
                    {!! navbar_link('note.create', 'plus', 'add') !!}
                    {!! navbar_link('labels', 'tags', 'labels')!!}
                    {!! navbar_link('reminders', 'bell', 'reminders') !!}
                    {!! navbar_link('upcoming', 'calendar', 'upcoming') !!}
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    <li class="navbar-text nav-search-label">@icon('search')</li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">@icon('user') {{ Auth::user()->name }} <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            {!! navbar_link('settings', 'cog fa-fw', 'settings') !!}
                            {!! navbar_link('stats', 'bar-chart fa-fw', 'stats') !!}
                            {!! navbar_link('user.logout', 'sign-out fa-fw', 'logout') !!}
                            {!! navbar_link('about', 'info-circle fa-fw', 'about') !!}
                        </ul>
                    </li>
                </ul>
                <form action="" method="post" class="navbar-form navbar-right hidden">
                    <div class="input-group">
                        <input type="search" name="search" class="form-control" placeholder="@lang('app.menu.search-placeholder')" value="" required>
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-primary">@icon('search')</button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </nav>

    <main class="container">
        @if (Session::has('message'))
            <div class="alert alert-{{ Session::get('message_type') ?: 'success' }} alert-fixed">
                @if (Session::get('message_raw') === true)
                <p>{!! Session::get('message') !!}</p>
                @else
                <p>{{ Session::get('message') }}</p>
                @endif
            </div>
        @endif

        @yield('content')
    </main>

    <script src="{!! asset('assets/js/codice.js') !!}"></script>
    @yield('footer')
</body>
</html>