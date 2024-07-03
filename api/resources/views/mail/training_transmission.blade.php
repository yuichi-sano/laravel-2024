<!DOCTYPE html>
<html>
<head>
    <title>{{ $subject }}</title>
</head>
<body>
{!! $content !!}
@includeIf('web.mail.unsubscribe')
</body>
</html>
