<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>HTTP {{ $code }} • Codice</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,400,500,700&amp;subset=latin,latin-ext">
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
            <a class="navbar-brand" href="{{ route('index') }}"><img alt="Codice" src="{!! asset('assets/images/brand.png') !!}"></a>
        </div>
        <div class="collapse navbar-collapse" id="navigation">
            <ul class="nav navbar-nav">
                <li><a href="{{ route('index') }}">Home</a></li>
                <li><a href="{!! config('services.website.url') !!}">Codice Website</a></li>
                <li><a href="{!! config('services.website.issues') !!}">Report a Bug</a></li>
            </ul>
        </div>
    </div>
</nav>

<main class="container">
    <div class="codice-container">
        <h1 class="app-error">HTTP {{ $code }}</h1>
        <h2 class="app-error">{{ trans("error.http.$code") }}</h2>
    </div>
</main>

</body>
</html>
