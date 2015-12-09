<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>{{ $title }} • Codice</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700,400italic&amp;subset=latin,latin-ext">
    <link rel="stylesheet" href="{!! asset('assets/css/codice.css') !!}">
</head>
<body>
    <main class="container">
        <h1 class="app-error">{{ $error }}</h1>
        <h2 class="app-error">{{ $message }}</h2>
        <h3 class="app-error">
            <a href="{!! route('index') !!}">@lang('app.error.back-to-index')</a>
        </h3>
    </main>
</body>
</html>