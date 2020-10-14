<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="{{ asset('/css/bootstrap/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('/fontawesome-free-5.15.1-web/css/all.css') }}">
    @yield('css')
    <title>Book Rental</title>
</head>
<body>
    <div id="app"></div>

    <script src="{{ asset('https://code.jquery.com/jquery-3.2.1.slim.min.js') }}" crossorigin="anonymous"></script>
    <script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js') }}" crossorigin="anonymous"></script>
    <script src="{{ asset('https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js') }}" crossorigin="anonymous"></script>
    <script src="{{ asset('/js/app.js') }}"></script>
    @yield('js')
</body>
</html>
