<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>{{ isset($title) ? $title . ' • ' : '' }}Codice</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,400,700,400italic&amp;subset=latin,latin-ext">
    <link rel="stylesheet" href="{!! asset('assets/css/codice.css') !!}">
</head>
<body id="login">
<main class="container">
    <noscript class="alert alert-warning alert-fixed alert-login">@lang('app.noscript')</noscript>

    @yield('content')
</main>

<script src="{!! asset('assets/js/locales/' . App::getLocale() . '.js') !!}"></script>
<script src="{!! asset('assets/js/codice.js') !!}"></script>
</body>
</html>