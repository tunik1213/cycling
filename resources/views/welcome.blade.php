@extends('layout')

@section('head')
    <title>{{env('APP_NAME')}}: сайт веломандрівника</title>
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


    @include('user.top',['userList'=>$topAuthors])

@endsection


@guest
        
@endguest