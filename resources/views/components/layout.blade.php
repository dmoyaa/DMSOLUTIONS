<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $metaTitle ?? 'default'}} </title>
</head>
<body>
@session('status')
<div>
    {{$value}}
</div>
@endsession
{{ $slot }}
<br>
@include('partials.navigation')
</body>
</html>
