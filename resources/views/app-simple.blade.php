<!DOCTYPE html>
<html lang="pl" class="no-js">
<head>
    <meta charset="utf-8">
    <title>{{ isset($title) ? $title . ' • ' : '' }}Codice</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>(function(H){H.className=H.className.replace(/\bno-js\b/,'js')})(document.documentElement)</script>
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700&amp;subset=latin,latin-ext">
    <link rel="stylesheet" href="{!! asset_versioned('assets/css/codice.css') !!}">
</head>
<body id="login">
<main class="container">
    <noscript class="alert alert-warning alert-fixed alert-login">@lang('app.noscript')</noscript>

    @yield('content')
</main>

<script src="{!! asset_versioned('assets/js/locales/' . App::getLocale() . '.js') !!}"></script>
<script src="{!! asset_versioned('assets/js/codice.js') !!}"></script>
</body>
</html>