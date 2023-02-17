@php
    if (request()->is('notifications')) {
        $common_notification_count = 0;
    } else {
        $common_notification_count = $user->unreadNotifications->count();
    }

    $editing_route = App\Models\Route::current_editing();
    if(!empty($editing_route)) {
        $editing_route_sights_count = $editing_route->sights()->count();
    } else {
        $editing_route_sights_count = 0;
    }

    $total_notification_count = $common_notification_count;
    if ($user->moderator) {
        $unmoderated_count = \App\Models\Sight::unmoderated_count();
        $unmoderated_versions = \App\Models\SightVersion::unmoderated_count();
        $unmoderated_feedback = \App\Models\Feedback::unmoderated_count();
        $unmoderated_routes = \App\Models\Route::unmoderated_count();
        $total_notification_count = $total_notification_count
            + $unmoderated_count
            + $unmoderated_versions
            + $unmoderated_feedback
            + $unmoderated_routes
        ;
    }
        
        
@endphp

<div class="dropdown">
    <div class="btn dropdown-toggle" id="userMenu" type="button">
        <div id="header-avatar-container">
            <img class="user-avatar" src="data:image/jpeg;base64,{{base64_encode($user->avatar)}}" />
            @if($common_notification_count > 0)
                <span id="user-notification-badge" class="badge">{{$total_notification_count}}</span>
            @endif
        </div>
        <span>{{ $user->firstname }}</span>
    </div>
    <ul id="user-menu-list" class="dropdown-menu">
        <li><a class="dropdown-item" href="{{route('userProfile')}}"><i class="fas fa-user"></i>Мій профіль</a></li>
        <li>
            <a class="dropdown-item" href="{{route('notifications')}}">
                <i class="fas fa-bell"></i>Сповіщення
                @if($common_notification_count > 0)
                    <span class="badge">{{$common_notification_count}}</span>
                @endif
            </a>
        </li>
        <li><a class="dropdown-item" id="add-header-button" href={{route('sights.create')}}><i class="fas fa-plus"></i>Додати локацію</a></li>

        <li><a class="dropdown-item" id="add-header-button" href={{route('routes.edit')}}><i class="fas fa-route"></i>
        @if(empty($editing_route))
            Створити веломаршрут
        @else
            @if(empty($editing_route->name))
                Мiй веломаршрут 
            @else
                {{$editing_route->name}}
            @endif

            @if($editing_route_sights_count > 0)
                <span id="my-route-count" class="badge">{{$editing_route_sights_count}}</span>
            @endif
        @endif
        </a></li>

        <li><a class="dropdown-item" href="/logout"><i class="fas fa-sign-out-alt"></i>Вийти</a></li>
        @if($user->moderator)
            <li><a class="dropdown-item admin-item" href="{{route('admin')}}"><i class="fas fa-users-cog"></i>Адмiнiстратор</a></li>
            <li><a class="dropdown-item admin-item" href="{{route('moderation')}}">
                <i class="fas fa-user-check"></i>
                Модерацiя
                @if($unmoderated_count > 0)
                    <span class="badge">{{$unmoderated_count}}</span>
                @endif
                </a>
            </li>
            <li><a class="dropdown-item admin-item" href="{{route('sights.edits')}}">
                <i class="fas fa-edit"></i>
                Правки
                @if($unmoderated_versions > 0)
                    <span class="badge">{{$unmoderated_versions}}</span>
                @endif
                </a>
            </li>
            <li><a class="dropdown-item admin-item" href="{{route('feedback.new')}}">
                <i class="fa-solid fa-comment-dots"></i>
                Вiдгуки
                @if($unmoderated_feedback > 0)
                    <span class="badge">{{$unmoderated_feedback}}</span>
                @endif
                </a>
            </li>
            <li><a class="dropdown-item admin-item" href="{{route('routes.new')}}">
                <i class="fa-solid fa-comment-dots"></i>
                Маршрути
                @if($unmoderated_routes > 0)
                    <span class="badge">{{$unmoderated_routes}}</span>
                @endif
                </a>
            </li>
        @endif
    </ul>
</div>