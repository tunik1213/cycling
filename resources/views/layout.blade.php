@php(
    $user = Auth::user()
)

<!DOCTYPE html>
<html lang="uk">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="MobileOptimized" content="320"/>
    <meta name="HandheldFriendly" content="true"/>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.0.0/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css">

    @yield('head')

    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">

    <meta name="theme-color" content="#f3fdff">

    @yield('markup')

    @if(env('APP_DEBUG'))
        <link rel="stylesheet" href="/leaflet.css">
        <link rel="stylesheet" href="/MarkerCluster.css">
        <link rel="stylesheet" href="/app.css">
    @else
        @include('production_asserts',['type' => 'css'])
    @endif

</head>
<body>
    <div id="header">
      <div class="container" id="header-container">
        <a class="navbar-brand" href="{{env('APP_URL')}}">
            <img id="header-logo-image" src="{{asset('images/logo.png')}}" />
            <div id="header-logo-text">Velocian</div>
        </a>

        <div id="right-header">
            @guest
                <a id="right-header-login-link" href="{{route('strava_login')}}"><i class="fas fa-user"></i>Вхід</a>
            @else
                @include('user.account_control')
            @endguest
        </div>
      </div>
    </div>

    @yield('banner')

    <!-- Begin page content -->
    <main role="main" class="container">
        @yield('content')
    </main>

    <footer class="footer">
      <div class="container">
        <ul id="footer-links">
            <li><a href="/">На головну</a></li>
            <li><a href="/about">Про проєкт</a></li>
            <li><a href="{{route('sights.list')}}">Локації</a>
            <li><a href="{{route('routes.list')}}">Маршрути</a>
            @auth
                <li><a class="text-highlight" href="{{route('feedback.form')}}">Залишити відгук</a></li>
            @endauth
        </ul>
      </div>
    </footer>

    <div id="scroll-top-button"><i class="fas fa-angle-up"></i></div>

<script src="https://code.jquery.com/jquery-3.6.3.min.js" integrity="sha256-pvPw+upLPUjgMXY0G+8O0xUf+/Im1MZjXxxgOcBQBXU=" crossorigin="anonymous"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>

<!-- jQuery Modal -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.2/jquery.modal.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.2/jquery.modal.css"/>


@if(env('APP_DEBUG'))
    <script src="/leaflet.js"></script>
    <script src="/leaflet.markercluster.js"></script>
    <script src="/app.js"></script>
@else
    @include('production_asserts',['type' => 'js'])
@endif

@yield('javascript')

</body>
</html>