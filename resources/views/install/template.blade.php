<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>{{ $title }} • @lang('install.title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,400,700,400italic&amp;subset=latin,latin-ext">
    <link rel="stylesheet" href="{!! asset_versioned('assets/css/codice.css') !!}">
</head>
<body class="install">
    <main class="container">
        <div class="progress install-progress">
            <div class="progress-bar progress-bar-success progress-bar-striped" style="width: {{ $progress }}%">
                <span class="sr-only">{{ $progress}}%</span>
            </div>
        </div>
        <h2 class="page-heading info">{{$title }} <span class="page-heading-aside">@lang('install.step') {{ $step }}/6</span></h2>
        <div class="codice-container">
            @yield('content')
        </div>
    </main>

    <script src="{!! asset_versioned('assets/js/codice.js') !!}"></script>
</body>
</html>