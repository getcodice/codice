<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>@lang('reminder.email.subject')</title>
</head>
<body>
    {!!
        trans('reminder.email.content', [
            'expires' => $note->expires_at_fmt,
            'name' => e($user->name),
        ])
    !!}
    <br />
    <hr />
    {{-- Note will most likely have own <p>, so spacing is not necessary --}}

    {!! $note->content !!}
</body>
</html>