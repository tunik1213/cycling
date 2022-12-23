@php
    $mainImg = null; $userImg = null;
    if($actList->user) {
        $userImg = $actList->user->avatar;
    }

    if($actList->sight) {
        $mainImg = $actList->sight->image;
    } elseif ($actList->route) {
        $mainImg = $actList->route->logo_image;
    }

@endphp

<div class="row">
    @if(!empty($mainImg))
        
        <div class="col-sm-3 col-xs-12">
            <div class="act-list-main-img">
                <img src="data:image/jpeg;base64,{{base64_encode($mainImg)}}" />
                @if(!empty($userImg))
                <div class="act-list-user-img">
                    <img src="data:image/jpeg;base64,{{base64_encode($userImg)}}"/>
                </div>
                @endif
            </div>

        </div>

    @endif

    <div class="col-sm-9 col-xs-12 activities-header-info">

        @if($actList->user)
            <h2>Заїзди @include('user.link',['user'=>$actList->user])</h2>
        @endif

        @if($actList->sight)
            <h4>Локація @include('sights.link',['sight'=>$actList->sight])</h4>
        @endif

        @if($actList->route)
            <h4>Маршрут @include('routes.link',['route'=>$actList->route])</h4>
        @endif

    </div>
</div>