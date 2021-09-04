@php(
    $user = Auth::user()
)

<!DOCTYPE html>
<html lang="ru">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="MobileOptimized" content="320"/>
    <meta name="HandheldFriendly" content="true"/>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="theme-color" content="">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    @if(env('APP_DEBUG'))
        <script src="{{ asset('js/lib/jquery.js') }}"></script>
        <script src="{{ asset('js/lib/bootstrap.bundle.js') }}"></script>
        <script src="{{ asset('js/lib/popper.min.js') }}"></script>
        <script src="{{ asset('js/lib/mdb.js') }}"></script>
        <script src="{{ asset('js/lib/jquery.jgrowl.js') }}"></script>
        <script src="{{asset('js/lib/tinymce.js')}}" referrerpolicy="origin"></script>
        <script src="{{ asset('js/engine.js') }}"></script>

        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
        <link href="{{ asset('css/lib/mdb.css') }}" rel="stylesheet">
        <link href="{{ asset('css/lib/jquery.jgrowl.css') }}" rel="stylesheet">
    @else
        @include('layouts.production_asserts')
    @endif

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">

    <!-- jQuery Modal -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css"/>

    @yield('head')

    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <link rel="shortcut icon" href="{{asset('/favicon.ico')}}">

    @yield('markup')

</head>
<body>

    <div id="#header">
        <div id="left-header">
            <a id="header-logo" href="/">Лого</a>
        </div>
        <div id="right-header">
            <a href="{{route('strava_login')}}">Вхiд</a>
        </div>
    </div>

    <div id="#body">

<hr/><br/>
        @php(var_dump($user))

        <hr/><br/>
        @yield('content')

    </div>

    <div id="#footer">

    </div>



</body>
</html>