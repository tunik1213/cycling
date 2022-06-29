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
        <a class="navbar-brand" href="/">Velocian</a>
        <div id="right-header">
            @guest
                <a id="right-header-login-link" href="{{route('strava_login')}}"><i class="fas fa-user"></i>Вхід</a>
            @else
            <div class="dropdown">
                <div class="btn dropdown-toggle" id="userMenu" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <img class="user-avatar" src="data:image/jpeg;base64,{{base64_encode($user->avatar)}}" />
                    {{ $user->firstname }}
                </div>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                    <li><a class="dropdown-item" href="{{route('userProfile')}}"><i class="fas fa-user"></i>Мій профіль</a></li>
                    <li><a class="dropdown-item" id="add-header-button" href={{route('sights.create')}}><i class="fas fa-plus"></i>Додати локацію</a></li>

                    @php($editing_route = App\Models\Route::current_editing())
                    <li><a class="dropdown-item" id="add-header-button" href={{route('routes.edit')}}><i class="fas fa-route"></i>
                    @if(empty($editing_route))
                        Створити веломаршрут
                    @else
                        @if(empty($editing_route->name))
                            Мiй веломаршрут 
                        @else
                            {{$editing_route->name}}
                        @endif
                        <span id="my-route-count" class="badge">{{$editing_route->sights()->count()}}</span>
                    @endif
                    </a></li>

                    <li><a class="dropdown-item" href="/logout"><i class="fas fa-sign-out-alt"></i>Вийти</a></li>
                    @if($user->moderator)
                        <li><a class="dropdown-item admin-item" href="{{route('admin')}}"><i class="fas fa-users-cog"></i>Адмiнiстратор</a></li>
                        <li><a class="dropdown-item admin-item" href="{{route('moderation')}}">
                            <i class="fas fa-user-check"></i>
                            Модерацiя
                            <span class="badge">{{\App\Models\Sight::unmoderated_count()}}</span>
                            </a>
                        </li>
                        <li><a class="dropdown-item admin-item" href="{{route('sights.edits')}}">
                            <i class="fas fa-edit"></i>
                            Правки
                            <span class="badge">{{\App\Models\SightVersion::unmoderated_count()}}</span>
                            </a>
                        </li>
                        <li><a class="dropdown-item admin-item" href="{{route('feedback.new')}}">
                            <i class="fa-solid fa-comment-dots"></i>
                            Вiдгуки
                            <span class="badge">{{\App\Models\Feedback::unmoderated_count()}}</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
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
            <li><a class="text-highlight" href="{{route('feedback.form')}}">Залишити відгук</a></li>
        </ul>
      </div>
    </footer>

    <div id="scroll-top-button"><i class="fas fa-angle-up"></i></div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"></script>

<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>


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