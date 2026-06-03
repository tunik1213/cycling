@extends('layout')

@section('head')
    <title>{{env('APP_NAME')}}: сайт веломандрівника</title>
    <meta name="description" content="Проєкт створено для велотуристів України, які користуються сервісом Strava. Система аналізує заїзди користувачів і підраховує статистику відвідування різноманітних локацій. Вимірюй подорожі не в кілометрах, а у відвіданих цікавих місцях!">
    <meta property="og:image" content="{{asset('/images/welcome-image-winter.jpg')}}" />
@endsection


@section('banner')

@guest

    @include('user.login_partial')

@endguest


@endsection

@section('content')

    

    @guest
        
    @else

        @include('sights.top',['sightList'=>$topSights])

    @endguest

    @include('user.top',['userList'=>$topUsers])

<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-9397290056752587"
     crossorigin="anonymous"></script>
<!-- adaptive -->
<ins class="adsbygoogle"
     style="display:block"
     data-ad-client="ca-pub-9397290056752587"
     data-ad-slot="2502468467"
     data-ad-format="auto"
     data-full-width-responsive="true"></ins>
<script>
     (adsbygoogle = window.adsbygoogle || []).push({});
</script>

    @include('user.top',['userList'=>$topAuthors])

@endsection


@guest
        
@endguest