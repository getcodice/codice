<!DOCTYPE html>
<html lang="pl" class="no-js">
<head>
    <meta charset="utf-8">
    <title>{{ isset($title) ? $title . ' • ' : '' }}Codice</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>(function(H){H.className=H.className.replace(/\bno-js\b/,'js')})(document.documentElement)</script>
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700&amp;subset=latin,latin-ext">
    <link rel="stylesheet" href="{!! asset('assets/css/codice.css') !!}">
    <?php
    /**
     * Before closing `</head>` tag
     *
     * @since 0.4
     */
    ?>
    @hook('template.header')
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
                    {!! App::make('menu.main')->render() !!}
                </ul>

                <ul class="nav navbar-nav navbar-right">
                    @if (empty(Request::query('query')))
                    <li class="navbar-text nav-search-label">@icon('search')</li>
                    @endif
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            @icon('user') {{ Auth::user()->name ?: Auth::user()->email }} <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            {!! App::make('menu.user')->render() !!}
                        </ul>
                    </li>
                </ul>
                <form action="{{ route('search') }}" method="get" class="navbar-form navbar-right {{ Request::query('query') ? '' : 'hidden' }}">
                    <div class="input-group">
                        <input type="search" name="query" class="form-control" value="{{ Request::query('query') }}" placeholder="@lang('app.menu.search-placeholder')" value="" required>
                        <span class="input-group-btn">
                            <button type="submit" class="btn btn-primary">@icon('search')</button>
                        </span>
                    </div>
                </form>
            </div>
        </div>
    </nav>

    <main class="container">
        <noscript class="alert alert-warning alert-fixed">@lang('app.noscript')</noscript>
        @if (Session::has('message'))
            <div class="alert alert-{{ Session::get('message_type') ?: 'info' }} alert-fixed">
                @if (Session::get('message_raw') === true)
                <p>{!! Session::get('message') !!}</p>
                @else
                <p>{{ Session::get('message') }}</p>
                @endif
            </div>
        @endif

        <?php
        /**
         * Before the content is rendered
         *
         * @since 0.4
         */
        ?>
        @hook('template.content.before')
        @yield('content')
        <?php
        /**
         * After the content is rendered
         *
         * @since 0.4
         */
        ?>
        @hook('template.content.after')
    </main>

    <script src="{!! asset('assets/js/locales/' . Auth::user()->options['language'] . '.js') !!}"></script>
    <script src="{!! asset('assets/js/codice.js') !!}"></script>
    @yield('footer')
    <?php
    /**
     * Right before closing `</body>`
     *
     * @since 0.4
     */
    ?>
    @hook('template.footer')
</body>
</html>
