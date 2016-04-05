<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>@lang('user.login.title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Roboto:300,400,700,400italic&amp;subset=latin,latin-ext">
    <link rel="stylesheet" href="{!! asset('assets/css/codice.css') !!}">
</head>
<body id="login">
    <main class="container">
        <noscript class="alert alert-warning alert-fixed alert-login">@lang('app.noscript')</noscript>
        @if (Session::has('message'))
            <div class="alert alert-danger alert-fixed alert-login">
                <p>{{ Session::get('message') }}</p>
            </div>
        @endif

        {!! BootForm::open()->action(route('user.login'))->class('form-login') !!}
            <h2 class="form-login-heading">@lang('user.login.title')</h2>
            {!! BootForm::email(trans('user.login.email'), 'email')->placeholder(trans('user.login.email'))->required()->autofocus()->hideLabel() !!}
            {!! BootForm::password(trans('user.login.password'), 'password')->placeholder(trans('user.login.password'))->required()->hideLabel() !!}
            {!! BootForm::submit(trans('user.login.submit'), 'btn-lg btn-block') !!}
        {!! BootForm::close() !!}
    </main>

    <script src="{!! asset('assets/js/jquery.min.js') !!}"></script>
    <script src="{!! asset('assets/js/bootstrap.min.js') !!}"></script>
    <script src="{!! asset('assets/js/codice.js') !!}"></script>
</body>
</html>