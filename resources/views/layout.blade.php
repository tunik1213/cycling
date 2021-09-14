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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css">

    @yield('head')

    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <link rel="shortcut icon" href="{{asset('/favicon.ico')}}">

    @yield('markup')

    <link rel="stylesheet" href="/app.css">

</head>
<body>

    <div class="header">
      <div class="container">
        <a class="navbar-brand" href="/">Лого</a>
        <div id="right-header">
            @guest
                <a href="{{route('strava_login')}}">Вхід</a>
            @else
            <div class="dropdown">
                <button class="btn dropdown-toggle" id="userMenu" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="user-avatar" src="{{route('userAvatar')}}" />
                    {{ $user->firstname }}
                </button>
                <ul class="dropdown-menu" aria-labelledby="userMenu">
                    <li><a class="dropdown-item" href="{{route('home')}}"><i class="fas fa-user"></i>Мій профіль</a></li>
                    <li><a class="dropdown-item" href="/logout"><i class="fas fa-sign-out-alt"></i>Вийти</a></li>
                </ul>
            </div>
            @endguest
        </div>
      </div>
    </div>

    <!-- Begin page content -->
    <main role="main" class="container">
        @yield('content')
    </main>

    <footer class="footer">
      <div class="container">
        <ul id="footer-links">
            <li><a href="/">На головну</a></li>
            <li><a href="#">Про проєкт</a></li>
            <li><a href="#">Залишити відгук</a></li>
        </ul>
      </div>
    </footer>

<script
  src="https://code.jquery.com/jquery-3.6.0.slim.min.js"
  integrity="sha256-u7e5khyithlIdTpu22PHhENmPcRdFiHRjhAuHcs05RI="
  crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>