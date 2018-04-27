<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
        <link href="/css/app.css" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
            }
        </style>
    <title>Inbox Gmail</title>
    </head>
    <body>
    <h1>Google Mail Login</h1>
    @if(LaravelGmail::check())
        <a class="btn btn-danger" href="{{ route('logout') }}">Logout</a>
    @else
        <a class="btn btn-success" href="{{ route('account-login') }}">login to Gmail</a>
    @endif
    </body>

</html>
